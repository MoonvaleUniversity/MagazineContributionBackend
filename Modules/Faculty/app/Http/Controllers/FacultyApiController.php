<?php

namespace Modules\Faculty\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Faculty\App\Http\Requests\StoreFacultyApiRequest;
use Illuminate\Http\Request;
use Modules\Faculty\App\Http\Requests\DeleteFacultyApiRequest;
use Modules\Faculty\App\Http\Requests\UpdateFacultyApiRequest;
use Modules\Faculty\App\Http\Requests\ViewFacultyApiRequest;
use Modules\Faculty\App\Http\Resources\FacultyApiResource;
use Modules\Faculty\Services\FacultyApiServiceInterface;

class FacultyApiController extends Controller
{
    protected $facultyApiRelations;

    public function __construct(protected FacultyApiServiceInterface $facultyApiService)
    {
        $this->facultyApiRelations = [];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(ViewFacultyApiRequest $request)
    {
        [$limit, $offset] = getLimitOffsetFromRequest($request);
        [$noPagination, $pagPerPage] = getNoPaginationPagPerPageFromRequest($request);
        $conds = $this->getFilterConditions($request);

        $faculties = $this->facultyApiService->getAll($this->facultyApiRelations, $limit, $offset, $noPagination, $pagPerPage, $conds);
        $data = [
            'faculties' => boolval($noPagination) || boolval($pagPerPage) ? FacultyApiResource::collection($faculties) : FacultyApiResource::collection($faculties)->response()->getData(true)
        ];
        return apiResponse(true, 'Data retrived successfully', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFacultyApiRequest $request)
    {
        $validatedData = $request->validated();
        $faculties = $this->facultyApiService->create($validatedData, $request->file('image'));
        $data = [
            'faculties' => new FacultyApiResource($faculties)
        ];
        return apiResponse(true, 'Data Store Successfully', $data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $faculties = $this->facultyApiService->get($id);
        $data = [
            'faculties' => new FacultyApiResource($faculties)
        ];
        try {
            return apiResponse(true, "Show data successfully", $data);
        } catch (\Exception $e) {
            return apiResponse(false, "No Data", errors: ['credentials' => ['The credentials you provided is incorrect.']]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFacultyApiRequest $request, string $id)
    {
        $validatedData = $request->validated();
        try {
            $faculties = $this->facultyApiService->update($id, $validatedData);
            $data = [
                'faculties' => new FacultyApiResource($faculties)
            ];
            return apiResponse(true, "Update Data Successfully", $data, 200);
        } catch (\Exception $e) {
            return apiResponse(false, errors: ['credentials' => ['Undefined Academic id']]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteFacultyApiRequest $request, string $id)
    {
        $deleted = $this->facultyApiService->delete($id);
        return $deleted ? apiResponse(true, "Successfully Deleted") : apiResponse(false, errors: ["404 Not Found"], statusCode: 404);
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
            'name' => $request->name,
            'student@@id' => $request->student_id,
            'user@@id' => $request->user_id,
        ];
    }
}
