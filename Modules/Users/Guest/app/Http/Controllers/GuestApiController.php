<?php

namespace Modules\Users\Guest\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Shared\Email\EmailServiceInterface;
use Modules\Users\Guest\App\Http\Requests\StoreGuestApiRequest;
use Modules\Users\Guest\App\Http\Requests\UpdateGuestApiRequest;
use Modules\Users\Guest\App\Http\Resources\GuestApiResource;
use Modules\Users\Guest\Services\GuestApiServiceInterface;

class GuestApiController extends Controller
{
    protected $guestApiRelations;
    public function __construct(protected GuestApiServiceInterface $guestApiService, protected EmailServiceInterface $emailService) {}


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        [$limit, $offset] = getLimitOffsetFromRequest($request);
        [$noPagination, $pagPerPage] = getNoPaginationPagPerPageFromRequest($request);
        $conds = $this->getFilterConditions($request);

        $guests = $this->guestApiService->getAll($this->guestApiRelations, $limit, $offset, $noPagination, $pagPerPage, $conds);
        $data = [
            'guests' => GuestApiResource::collection($guests)
        ];
        return apiResponse(true, 'Data retrieved successfully', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGuestApiRequest $request)
    {
        $validatedData = $request->validated();
        $guests = $this->guestApiService->create($validatedData);
        $data = [
            'guests' => new GuestApiResource($guests)
        ];
        $this->emailService->send('message', $guests->email, 'Account Review', ['body' => 'Your account is created. Once our marketing coordinators have approved your account, you will be able to use this account to use our services.']);
        return apiResponse(true, 'guests created successfully', $data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $guests = $this->guestApiService->get($id, $this->guestApiRelations);
        $data = [
            'guests' => new GuestApiResource($guests)
        ];
        return apiResponse(true, 'Data retrieved successfully', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGuestApiRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $guests = $this->guestApiService->update($id, $validatedData);
        $data = [
            'guests' => new GuestApiResource($guests)
        ];
        return apiResponse(true, 'User updated successfully', $data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $guests = $this->guestApiService->delete($id);
        $data = [
            'guests' => new GuestApiResource($guests)
        ];
        return apiResponse(true, 'User updated successfully', $data);
    }

    public function approve(string $id)
    {
        $guestData['is_approved'] = 1;
        $guests = $this->guestApiService->update($id, $guestData);
        $data = [
            'guests' => new GuestApiResource($guests)
        ];
        $this->emailService->send('message', $guests->email, 'Account Review', ['body' => 'Your account is approved. You can now login with the credentials and use our services.']);
        return apiResponse(true, 'User updated successfully', $data);
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
            'email' => $request->email,
            'academic_year_id' => $request->academic_year_id,
            'faculty_id' => $request->faculty_id
        ];
    }
}
