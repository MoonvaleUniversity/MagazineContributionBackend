<?php

namespace Modules\Contribution\App\Http\Controllers;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Contribution\App\Http\Requests\DeleteContributionApiRequest;
use Modules\Contribution\App\Http\Requests\StoreContributionApiRequest;
use Modules\Contribution\App\Http\Requests\ViewContributionApiRequest;
use Modules\Contribution\Services\ContributionApiServiceInterface;
use Modules\Shared\Email\EmailServiceInterface;
use Modules\Users\User\Services\UserApiServiceInterface;

class ContributionApiController extends Controller
{
    protected $contributionApiRelations;
    public function __construct(protected ContributionApiServiceInterface $contributionApiService, protected UserApiServiceInterface $userApiService, protected EmailServiceInterface $emailService)
    {
        $this->contributionApiRelations = ['images', 'user', 'user.faculty'];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        [$limit, $offset]            = getLimitOffsetFromRequest($request);
        [$noPagination, $pagPerPage] = getNoPaginationPagPerPageFromRequest($request);
        $conds                       = $this->getFilterConditions($request);
        $contribution = $this->contributionApiService->getAll($this->contributionApiRelations, $limit, $offset, $noPagination, $pagPerPage, $conds);
        $data         = [
            'contributions' => $contribution,
        ];
        return apiResponse(true, 'Data retrieve successfully', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContributionApiRequest $request)
    {
        $validatedData = $request->validated();
        $studentUser = $this->userApiService->get($validatedData['user_id']);
        $marketingCoordinator = $this->userApiService->get(
            conds: [
                'faculty_id' => $studentUser->faculty_id,
                'role' => Role::MARKETING_COORDINATOR->label()
            ]
        );

        if (!$studentUser) {
            return apiResponse(false, 'User email not found', [], 400);
        }
        if (!$marketingCoordinator) {
            return apiResponse(false, 'Marketing coordinator not found for this faculty.');
        }

        $contribution = $this->contributionApiService->get(conds: ['user_id' => $validatedData['user_id'], 'name' => $validatedData['name']]);
        if ($contribution) {
            return apiResponse(false, 'You already created this contribution.', statusCode: 403, errors: ['contribution' => 'You already created thi contribution.']);
        }

        $contribution = $this->contributionApiService->create($validatedData, $request->file('doc'), $request->file('images'));
        $data         = [
            'contributions' => $contribution,
        ];

        $this->emailService->send('submissionEmail', $marketingCoordinator->email, 'Student`s contributions submission', ['student' => $studentUser]);
        $this->emailService->send('submissionEmail', $studentUser->email, 'Submission Successful', ['student' => $studentUser]);

        return apiResponse(true, 'Contribution stored successfully', $data);
    }

    /**
     * Display the specified resource.
     */
    public function show(ViewContributionApiRequest $request, $id)
    {
        $contribution = $this->contributionApiService->get($id, relations: $this->contributionApiRelations);
        if (!$contribution) {
            return apiResponse(false, 'Contribution not found', [], 404, ['contribution' => 'Contribution not found']);
        }
        $data = [
            'contributions' => $contribution
        ];
        return apiResponse(true, "Show data successfully", $data);
    }

    public function emailAuto()
    {
        $this->contributionApiService->automatic();
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    public function publish(string $id)
    {
        $data = $this->contributionApiService->updatePublish($id);
        return apiResponse(true, 'Contribution was published successfully', $data);
    }

    public function downloadZipFile(string $id)
    {
        $data = $this->contributionApiService->downloadZip($id);
        return $data;
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteContributionApiRequest $request, string $id)
    {
        $contribution = $this->contributionApiService->get($id, relations: $this->contributionApiRelations);
        $user = Auth::user();
        if (!$contribution) {
            return apiResponse(false, 'Contribution not found', [], 404, ['contribution' => 'Contribution not found']);
        }
        $name = $this->contributionApiService->delete($id);
        $this->emailService->send('delete-contribution-email', $contribution->user->email, 'Submission Deleted', ['user' => $user, 'contributionName' => $name]);

        return apiResponse(true, 'Contribution was deleted successfully', ['name' => $name]);
    }

    public function comment(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'content' => 'required'
        ]);
        $userId = Auth::user()->id;

        $contribution = $this->contributionApiService->comment($id, $userId, $validatedData['content']);

        $data = [
            'contributions' => $contribution
        ];

        return apiResponse(true, "Commented on contribution successfully", $data);
    }

    public function deleteComment(string $id)
    {
        $userId = Auth::user()->id;

        $contribution = $this->contributionApiService->deleteComment($id, $userId);

        $data = [
            'contributions' => $contribution
        ];

        return apiResponse(true, "Commented on contribution successfully", $data);
    }

    public function getComment(string $id)
    {
        $userId = Auth::user()->id;

        $contribution = $this->contributionApiService->get($id);

        $comment = $contribution->user_comments()
            ->wherePivot('user_id', $userId)
            ->first()?->pivot;

        $data = [
            'comment' =>   $comment
        ];

        return apiResponse(true, "Comment fetched successfully", $data);
    }

    public function voteContribution(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'type' => 'required|in:upvote,downvote'
        ]);
        $userId = Auth::user()->id;

        $contribution = $this->contributionApiService->vote($id, $userId, $validatedData['type']);

        $data = [
            'contributions' => $contribution
        ];

        return apiResponse(true, "Comment fetched successfully", $data);
    }

    public function saveContribution(string $id)
    {
        $userId = Auth::user()->id;

        $contribution = $this->contributionApiService->save($id, $userId);

        $data = [
            'contributions' => $contribution
        ];

        return apiResponse(true, "Contribution saved successfully", $data);
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Data Preparations
    //-------------------------------------------------------------------
    private function getFilterConditions(Request $request)
    {
        return [
            'name'                              => $request->name,
            'user_id'                           => $request->user_id,
            'user_id@@name'                     => $request->user_name,
            'user_id@@academic_year_id'         => $request->user_academic_year_id,
            'user_id@@faculty_id'               => $request->faculty_id,
            'closure_date_id'                   => $request->closure_date_id,
            'closure_date_id@@academic_year_id' => $request->closure_date_academic_year_id,
            'is_selected_for_publication'       => $request->is_selected_for_publication,
        ];
    }
}
