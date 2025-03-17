<?php

namespace Modules\Users\Admin\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Users\Admin\App\Http\Requests\StoreAdminRequest;
use Illuminate\Http\Request;
use Modules\Users\Admin\App\Http\Requests\UpdateAdminRequest;
use Modules\Users\Admin\App\Http\Resources\AdminApiResource;
use Modules\Users\Admin\Services\AdminApiServiceInterface;

class AdminApiController extends Controller
{
    protected $adminApiRelations;

    public function __construct(protected AdminApiServiceInterface $adminApiService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        [$limit, $offset] = getLimitOffsetFromRequest($request);
        [$noPagination, $pagPerPage] = getNoPaginationPagPerPageFromRequest($request);
        $conds = $this->getFilterConditions($request);

        $admin = $this->adminApiService->getAll($this->adminApiRelations, $limit, $offset, $noPagination, $pagPerPage, $conds);
        $data = [
            'admin' => AdminApiResource::collection($admin)
        ];
        return apiResponse(true, 'Data retrived successfully', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAdminRequest $request)
    {
        $validatedData = $request->validated();
        $admin = $this->adminApiService->create($validatedData);
        $data = [
            'admin' => new AdminApiResource($admin)
        ];
        return apiResponse(true, 'admin created successfully', $data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $admin = $this->adminApiService->get($id, $this->adminApiRelations);
        $data = [
            'admin' => new AdminApiResource($admin)
        ];
        return apiResponse(true, 'Data retrieved successfully', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdminRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $admin = $this->adminApiService->update($id, $validatedData);
        $data = [
            'admin' => new AdminApiResource($admin)
        ];
        return apiResponse(true, 'Admin updated successfully', $data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = $this->adminApiService->delete($id);
        return apiResponse(true,  'Deleted successfully', $data);
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
