<?php

namespace Modules\AcademicYear\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\AcademicYear\App\Http\Resources\AcademicYearApiResource;
use Illuminate\Http\Request;
use Modules\AcademicYear\Services\AcademicYearApiServiceInterface;
use Modules\Users\User\App\Http\Requests\StoreUserApiRequest;
use Modules\Users\User\App\Http\Requests\UpdateUserApiRequest;

class AcademicYearApiController extends Controller
{
    public function __construct(protected AcademicYearApiServiceInterface $academicYearApiService) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $academic_year = $this->academicYearApiService->getAll();
        $data = [
            'academic_year' => AcademicYearApiResource::collection($academic_year)
        ];
        return apiResponse(true, 'Data retrived successfully', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserApiRequest $request)
    {
        $validatedData = $request->validated();
        $academic_year = $this->academicYearApiService->create($validatedData);
        $data = [
            'academic_year' => new AcademicYearApiResource($academic_year)
        ];
        return apiResponse(true, 'Data Store Successfully', $data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $academic_year = $this->academicYearApiService->get($id);
        $data = [
            'academic_year' => new AcademicYearApiResource($academic_year)
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
    public function update(UpdateUserApiRequest $request, string $id)
    {
        $validatedData = $request->validated();
        try {
            $closureDate = $this->academicYearApiService->update($id, $validatedData);
            $data = [
                'closure_dates' => new AcademicYearApiResource($closureDate)
            ];
            return apiResponse(true, "Update Data Successfully", $data, 200);
        } catch (\Exception $e) {
            return apiResponse(false, errors: ['credentials' => ['Undefined Academic id']]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleted = $this->academicYearApiService->delete($id);
        return $deleted ? apiResponse(true, "Successfully Deleted") : apiResponse(false, errors: ["404 Not Found"], statusCode: 404);
    }
}
