<?php

namespace Modules\Users\Manager\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Users\Manager\App\Http\Requests\StoreManagerRequest;
use Modules\Users\Manager\App\Http\Requests\UpdateManagerRequest;
use Modules\Users\Manager\App\Http\Resources\ManagerApiResource;
use Modules\Users\Manager\Services\ManagerApiServiceInterface;

class ManagerApiController extends Controller
{

    protected $managerApiRelations;
    public function __construct(protected ManagerApiServiceInterface $managerApiService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        [$limit,$offset] =getLimitOffsetFromRequest($request);
        [$noPagination, $pagPerPage] = getNoPaginationPagPerPageFromRequest($request);
        $conds = $this->getFilterConditions($request);
        $managers = $this->managerApiService->getAll($this->managerApiRelations, $limit,$offset,$noPagination,$pagPerPage,$conds);
        $data = [
            'managers' => ManagerApiResource::collection($managers)
        ];
        return apiResponse(true,"Data retrieved successfully",$data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreManagerRequest $request)
    {
        $managerData = $request->validated();
        $manager = $this->managerApiService->create($managerData);
        $data = [
            'coordinator' => new ManagerApiResource($manager)
        ];
        return apiResponse(true, 'Manager created successfully', $data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $coordinator = $this->managerApiService->get($id, $this->managerApiRelations);
        $data = [
            'managers' => new ManagerApiResource($coordinator)
        ];
        return apiResponse(true, 'Data retrieved successfully', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateManagerRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $coordinator = $this->managerApiService->update($id, $validatedData);
        $data = [
            'managers' => new ManagerApiResource($coordinator)
        ];
        return apiResponse(true, 'Manager updated successfully', $data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $manager = $this->managerApiService->delete($id);
        $data = [
            'managers' => new ManagerApiResource($manager)
        ];
        return apiResponse(true, 'Deleted Manager successfully', $data);
    }

    private function getFilterConditions(Request $request)
    {
        return [
            'email' => $request->email,
            'academic_year_id' => $request->academic_year_id,
            'faculty_id' => $request->faculty_id
        ];
    }
}
