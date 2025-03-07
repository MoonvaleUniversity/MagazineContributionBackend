<?php

namespace Modules\Faculty\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Faculty\App\Http\Requests\StoreFacultyApiRequest;
use App\Http\Requests\UpdateFacultyApiRequest;
use Modules\Faculty\App\Http\Resources\FacultyApiResource;
use Modules\Faculty\Services\FacultyApiServiceInterface;

class FacultyApiController extends Controller
{
    public function __construct(protected FacultyApiServiceInterface $facultyApiService) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $faculty = $this->facultyApiService->getAll();
        $data = [
            'faculty' => FacultyApiResource::collection($faculty)
        ];
        return apiResponse(true, 'Data retrived successfully', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFacultyApiRequest $request)
    {
        $validatedData = $request->validated();
        $faculty = $this->facultyApiService->create($validatedData);
        $data = [
            'faculty' => new FacultyApiResource($faculty)
        ];
        return apiResponse(true, 'Data Store Successfully', $data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $faculty = $this->facultyApiService->get($id);
        $data = [
            'faculty' => new FacultyApiResource($faculty)
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
            $faculty = $this->facultyApiService->update($id, $validatedData);
            $data = [
                'faculty' => new FacultyApiResource($faculty)
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
        $deleted = $this->facultyApiService->delete($id);
        return $deleted ? apiResponse(true, "Successfully Deleted") : apiResponse(false, errors: ["404 Not Found"], statusCode: 404);
    }
}
