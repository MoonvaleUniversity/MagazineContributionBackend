<?php

namespace Modules\Contribution\App\Http\Controllers;

use Exception;
use App\Models\User;
use Mockery\Expectation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Shared\Email\EmailServiceInterface;
use Modules\Users\User\Services\UserApiServiceInterface;
use Modules\Contribution\Services\ContributionApiServiceInterface;
use Modules\Contribution\App\Http\Requests\StoreContributionApiRequest;

class ContributionApiController extends Controller
{
    public function __construct(protected ContributionApiServiceInterface $contributionApiService,  protected EmailServiceInterface $emailService, protected UserApiServiceInterface $userApiService) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contribution = $this->contributionApiService->getAll();
        $data = [
            'contributions' => $contribution
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
    public function destroy(string $id)
    {
        //
    }
}
