<?php

namespace Modules\ClosureDate\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ClosureDate\App\Http\Requests\ClosureApiRequest;
use Modules\ClosureDate\App\Http\Resources\ClosureResourceApi;
use Modules\ClosureDate\Services\ClosureDateApiServiceInterface;

use function Laravel\Prompts\error;

class ClosureDateApiController extends Controller
{
    public function __construct(protected ClosureDateApiServiceInterface $closureDateApiService) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $closure_data = $this->closureDateApiService->getAll();
        $data = [
            'closure_data'=>ClosureResourceApi::collection($closure_data)
        ];
        return apiResponse(true, 'Data retrieve successfully',$data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClosureApiRequest $request)
    {
        $validatedData = $request->validated();
        $data = $this->closureDateApiService->create($validatedData);
        $closureData = [
            'closure_data' => new ClosureResourceApi($data)
        ];
        return apiResponse(true, 'Data Store Successfully',$closureData);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = $this->closureDateApiService->get($id);
        $closureDate = [
            'closure_date' => new ClosureResourceApi($data)
        ];
        try{
            return apiResponse(true, "Show data successfully", $closureDate);
        }catch(\Exception $e){
            return apiResponse(false, "No Data", errors: ['credentials' => ['The credentials you provided is incorrect.']]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClosureApiRequest $request,  $id)
    {
        $validated = $request->validated();
        try{
            $closureDate = $this->closureDateApiService->update($id, $validated);
            return apiResponse(true,"Update Data Successfully", $closureDate, statusCode:200);
        }catch(\Exception $e){
            return apiResponse(false, errors: ['credentials' => ['Undefined Academic id']]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleted = $this->closureDateApiService->delete($id);
        return $deleted ? apiResponse(true,"Successfully Deleted") : apiResponse(false,errors:["404 Not Found"],statusCode:404);
    }
}
