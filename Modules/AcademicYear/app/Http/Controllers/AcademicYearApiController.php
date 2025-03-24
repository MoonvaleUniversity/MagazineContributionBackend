<?php

namespace Modules\AcademicYear\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\AcademicYear\App\Http\Requests\UpdateAcademicYearRequest;
use Modules\AcademicYear\App\Http\Resources\AcademicYearApiResource;
use Illuminate\Http\Request;
use Modules\AcademicYear\App\Http\Requests\DeleteAcademicYearApiRequest;
use Modules\AcademicYear\App\Http\Requests\StoreAcademicYearApiRequest;
use Modules\AcademicYear\App\Http\Requests\ViewAcademicYearApiRequest;
use Modules\AcademicYear\Services\AcademicYearApiServiceInterface;

class AcademicYearApiController extends Controller
{
    protected $academicYearApiRelations;

    public function __construct(protected AcademicYearApiServiceInterface $academicYearApiService) {}
    /**
     * Display a listing of the resource.
     */
    public function index(ViewAcademicYearApiRequest $request)
    {
        [$limit, $offset] = getLimitOffsetFromRequest($request);
        [$noPagination, $pagPerPage] = getNoPaginationPagPerPageFromRequest($request);
        $conds = $this->getFilterConditions($request);

        $academicYears = $this->academicYearApiService->getAll($this->academicYearApiRelations, $limit, $offset, $noPagination, $pagPerPage, $conds);
        $data = [
            'academic_years' => boolval($noPagination) || boolval($pagPerPage) ? AcademicYearApiResource::collection($academicYears) : AcademicYearApiResource::collection($academicYears)->response()->getData(true)
        ];
        return apiResponse(true, 'Data retrived successfully', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAcademicYearApiRequest $request)
    {
        $validatedData = $request->validated();
        $academicYears = $this->academicYearApiService->create($validatedData);
        $data = [
            'academic_years' => new AcademicYearApiResource($academicYears)
        ];
        return apiResponse(true, 'Data Store Successfully', $data);
    }

    /**
     * Display the specified resource.
     */
    public function show(ViewAcademicYearApiRequest $request, string $id)
    {
        $academicYears = $this->academicYearApiService->get($id);
        $data = [
            'academic_years' => new AcademicYearApiResource($academicYears)
        ];
        return apiResponse(true, "Show data successfully", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAcademicYearRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $academicYears = $this->academicYearApiService->update($id, $validatedData);
        $data = [
            'academic_years' => new AcademicYearApiResource($academicYears)
        ];
        return apiResponse(true, "Update Data Successfully", $data, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteAcademicYearApiRequest $request, string $id)
    {
        $deleted = $this->academicYearApiService->delete($id);
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
            'year_name' => $request->year_name,
            'closure_date@@id' => $request->closure_date_id,
        ];
    }
}
