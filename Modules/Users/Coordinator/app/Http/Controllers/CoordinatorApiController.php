<?php

namespace Modules\Users\Coordinator\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Users\Coordinator\App\Http\Resources\CoordinatorApiResource;
use Illuminate\Http\Request;
use Modules\Users\Coordinator\App\Http\Requests\StoreCoordinatorApiRequest;
use Modules\Users\Coordinator\App\Http\Requests\UpdateCoordinatorApiRequest;
use Modules\Users\Coordinator\Services\CoordinatorApiServiceInterface;

class CoordinatorApiController extends Controller
{
    protected $coordinatorApiRelations;

    public function __construct(protected CoordinatorApiServiceInterface $coordinatorApiService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        [$limit, $offset] = getLimitOffsetFromRequest($request);
        [$noPagination, $pagPerPage] = getNoPaginationPagPerPageFromRequest($request);
        $conds = $this->getFilterConditions($request);

        $coordinators = $this->coordinatorApiService->getAll($this->coordinatorApiRelations, $limit, $offset, $noPagination, $pagPerPage, $conds);
        $data = [
            'coordinators' => CoordinatorApiResource::collection($coordinators)
        ];
        return apiResponse(true, 'Data retrived successfully', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCoordinatorApiRequest $request)
    {
        $validatedData = $request->validated();
        $coordinator = $this->coordinatorApiService->create($validatedData);
        $data = [
            'coordinator' => new CoordinatorApiResource($coordinator)
        ];
        return apiResponse(true, 'Coordinator created successfully', $data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $coordinator = $this->coordinatorApiService->get($id, $this->coordinatorApiRelations);
        $data = [
            'coordinators' => new CoordinatorApiResource($coordinator)
        ];
        return apiResponse(true, 'Data retrived successfully', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCoordinatorApiRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $coordinator = $this->coordinatorApiService->update($id, $validatedData);
        $data = [
            'coordinator' => new CoordinatorApiResource($coordinator)
        ];
        return apiResponse(true, 'User updated successfully', $data);
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
            'email' => $request->email,
            'academic_year_id' => $request->academic_year_id,
            'faculty_id' => $request->faculty_id
        ];
    }
}
