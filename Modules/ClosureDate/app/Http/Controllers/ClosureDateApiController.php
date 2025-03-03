<?php

namespace Modules\ClosureDate\App\Http\Controllers;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ClosureDate\App\Models\ClosureDate;

use Modules\Contribution\App\Models\Contribution;
use Modules\ClosureDate\App\Http\Requests\ClosureApiRequest;
use Modules\ClosureDate\App\Http\Requests\StoreClosureDateApiRequest;
use Modules\ClosureDate\App\Http\Requests\UpdateClosureDateApiRequest;
use Modules\ClosureDate\App\Http\Resources\ClosureResourceApi;
use Modules\ClosureDate\Services\ClosureDateApiServiceInterface;

class ClosureDateApiController extends Controller
{
    public function __construct(protected ClosureDateApiServiceInterface $closureDateApiService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $closureDates = $this->closureDateApiService->getAll();
        $data = [
            'closure_dates' => ClosureResourceApi::collection($closureDates)
        ];
        return apiResponse(true, 'Data retrieve successfully', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClosureDateApiRequest $request)
    {
        $validatedData = $request->validated();
        $closureDate = $this->closureDateApiService->create($validatedData);
        $data = [
            'closure_dates' => new ClosureResourceApi($closureDate)
        ];
        return apiResponse(true, 'Data Store Successfully', $data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $closureDate = $this->closureDateApiService->get($id);
        $data = [
            'closure_dates' => new ClosureResourceApi($closureDate)
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
    public function update(UpdateClosureDateApiRequest $request,  $id)
    {
        $validatedData = $request->validated();
        try {
            $closureDate = $this->closureDateApiService->update($id, $validatedData);
            $data = [
                'closure_dates' => new ClosureResourceApi($closureDate)
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
        $deleted = $this->closureDateApiService->delete($id);
        return $deleted ? apiResponse(true, "Successfully Deleted") : apiResponse(false, errors: ["404 Not Found"], statusCode: 404);
    }
}
