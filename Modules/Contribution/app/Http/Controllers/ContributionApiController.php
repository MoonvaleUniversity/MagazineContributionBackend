<?php
namespace Modules\Contribution\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Contribution\App\Http\Requests\StoreContributionApiRequest;
use Modules\Contribution\Services\ContributionApiServiceInterface;
use Modules\Shared\Email\EmailServiceInterface;
use Modules\Users\User\Services\UserApiServiceInterface;

class ContributionApiController extends Controller
{
    protected $contributionApiRelations;
    public function __construct(protected ContributionApiServiceInterface $contributionApiService, protected UserApiServiceInterface $userApiService, protected EmailServiceInterface $emailService)
    {
        $this->contributionApiRelations = ['images'];
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
        $studentUser     = $this->userApiService->get($validatedData['user_id']);

        $contribution = $this->contributionApiService->create($validatedData, $request->file('doc'), $request->file('images'));
        $data         = [
            'contributions' => $contribution,
        ];

        $marketingCoordinator = $this->userApiService->getAll(
            conds: [
                'faculty_id' => $studentUser->faculty_id
            ],relations: ['roles'])
            ->filter(function($user) {
            return $user->roles->contains('name', 'Marketing Coordinator');})->first();

        if ($marketingCoordinator) {
            // dd($marketingCoordinator->email);
            $this->emailService->send('submissionEmail', $marketingCoordinator->email,'Student`s contributions submission',['student'=>$studentUser]);

        } else {
            return apiResponse(false, 'Marketing coordinator not found for this faculty.');
        }



        return apiResponse(true, 'Contribution stored successfully', $data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
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
            'closure_date_id'                   => $request->closure_date_id,
            'closure_date_id@@academic_year_id' => $request->closure_date_academic_year_id,
            'is_selected_for_publication'       => $request->is_selected_for_publication,
        ];
    }
}
