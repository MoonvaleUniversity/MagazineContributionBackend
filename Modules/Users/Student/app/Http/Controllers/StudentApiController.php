<?php

namespace Modules\Users\Student\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Users\Student\App\Http\Requests\StoreStudentApiRequest;
use Modules\Users\Student\App\Http\Requests\UpdateStudentApiRequest;
use Modules\Users\Student\App\Http\Resources\StudentApiResource;
use Modules\Users\Student\Services\StudentApiServiceInterface;

class StudentApiController extends Controller
{
    protected $studentApiRelations;
    public function __construct(protected StudentApiServiceInterface $studentApiService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        [$limit, $offset] = getLimitOffsetFromRequest($request);
        [$noPagination, $pagPerPage] = getNoPaginationPagPerPageFromRequest($request);
        $conds = $this->getFilterConditions($request);

        $students = $this->studentApiService->getAll($this->studentApiRelations, $limit, $offset, $noPagination, $pagPerPage, $conds);
        $data = [
            'students' => StudentApiResource::collection($students)
        ];
        return apiResponse(true, 'Data retrieved successfully', $data);
    }

     /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentApiRequest $request)
    {
        $validatedData = $request->validated();
        $students = $this->studentApiService->create($validatedData);
        $data = [
            'students' => new StudentApiResource($students)
        ];
        return apiResponse(true, 'Student created successfully', $data);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $id)
    {
        $students = $this->studentApiService->get($id, $this->studentApiRelations);
        $data = [
            'students' => new StudentApiResource($students)
        ];
        return apiResponse(true, 'Student retrieved successfully', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentApiRequest $request, int $id)
    {
        $validatedData = $request->validated();
        $students = $this->studentApiService->update($id, $validatedData);
        $data = [
            'students' => new StudentApiResource($students)
        ];
        return apiResponse(true, 'Student updated successfully', $data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $students = $this->studentApiService->delete($id);
        $data = [
            'students' => new StudentApiResource($students)
        ];
        return apiResponse(true, 'Student Deleted successfully', $data);
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
