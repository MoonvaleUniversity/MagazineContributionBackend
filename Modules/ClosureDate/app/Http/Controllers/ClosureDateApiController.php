<?php

namespace Modules\ClosureDate\App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use function Laravel\Prompts\error;
use App\Http\Controllers\Controller;
use Modules\ClosureDate\App\Models\ClosureDate;

use Modules\Contribution\App\Models\Contribution;
use Modules\ClosureDate\App\Http\Requests\ClosureApiRequest;
use Modules\ClosureDate\App\Http\Resources\ClosureResourceApi;
use Modules\ClosureDate\Services\ClosureDateApiServiceInterface;

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

     public function lock($id)
    {
        $contribution= Contribution::findOrFail($id);

        $closureDate = ClosureDate::where('id', $contribution->closure_date_id)
        ->orderBy('final_closure_date', 'desc')
        ->first();

        if ($closureDate && Carbon::now()->greaterThan($closureDate->final_closure_date)) {
            return apiResponse(false,'Submissions are closed because deadline has passed.',statusCode:403);
        }else{
            return apiResponse(true);
        }
    }
}
