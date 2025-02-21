<?php

namespace Modules\ClosureDate\App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\ClosureDate\App\Http\Requests\ClosureApiRequest;
use Modules\ClosureDate\App\Http\Resources\ClosureResourceApi;
use Modules\ClosureDate\Services\ClosureDateApiServiceInterface;

class ClosureDateApiController extends Controller
{
    protected ClosureDateApiServiceInterface $closureDateService;

    public function __construct(ClosureDateApiServiceInterface $closureDateService)
    {
        $this->closureDateService = $closureDateService;
    }

    public function index()
    {
        $closure_data = $this->closureDateService->getAll();
        $data = ClosureResourceApi::collection($closure_data);
        return response()->json(["closure_data"=>$data]);
    }

    public function store(ClosureApiRequest $request)
    {
        $validated = $request->validated();
        $closureDate = $this->closureDateService->create($validated);
        return response()->json(["closure_date"=>$closureDate], 201);
    }

    public function show($id)
    {
        $closureDate = $this->closureDateService->getById($id);
        return $closureDate ? response()->json($closureDate) : response()->json(['message' => 'Not Found'], 404);
    }

    public function update(ClosureApiRequest $request, $id)
    {
        $validated = $request->validated();
        try{
            $closureDate = $this->closureDateService->update($id, $validated);
            return response()->json([$closureDate,"success"=>"update successfully"]);
        }catch(\Exception $e){
            return response()->json(["message"=>"Undefined academic id"]);
        }
    }

    public function destroy($id)
    {
        $deleted = $this->closureDateService->delete($id);
        return $deleted ? response()->json(['message' => 'Deleted']) : response()->json(['message' => 'Not Found'], 404);
    }
}

