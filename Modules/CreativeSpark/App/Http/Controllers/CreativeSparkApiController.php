<?php

namespace Modules\CreativeSpark\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\CreativeSpark\App\Http\Requests\StoreCreativeSparkApiRequest;
use Modules\CreativeSpark\App\Http\Requests\UpdateCreativeSparkApiRequest;
use Modules\CreativeSpark\App\Http\Resources\CreativeSparkApiResource;
use Modules\CreativeSpark\Services\CreativeSparkApiServiceInterface;

class CreativeSparkApiController extends Controller
{
    public function __construct(protected CreativeSparkApiServiceInterface $creativeSparkApiService) {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        [$limit, $offset]            = getLimitOffsetFromRequest($request);
        [$noPagination, $pagPerPage] = getNoPaginationPagPerPageFromRequest($request);
        $conds                       = $this->getFilterConditions($request);

        $creativeSparks = $this->creativeSparkApiService->getAll($limit, $offset, $noPagination, $pagPerPage, $conds);
        $data = [
            'creative_sparks' => boolval($noPagination) || boolval($pagPerPage) ? CreativeSparkApiResource::collection($creativeSparks) : CreativeSparkApiResource::collection($creativeSparks)->response()->getData(true)
        ];

        return apiResponse(true, 'Data retrived successfully', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCreativeSparkApiRequest $request)
    {
        $validatedData = $request->validated();
        $creativeSpark = $this->creativeSparkApiService->create($validatedData, $request->file('image'));
        $data = [
            'creative-sparks' => new CreativeSparkApiResource($creativeSpark)
        ];
        return apiResponse(true, 'Data store successfully', $data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $creativeSpark = $this->creativeSparkApiService->get($id);
        $data = [
            'creative-sparks' => new CreativeSparkApiResource($creativeSpark)
        ];
        return apiResponse(true, 'Show data successfully', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCreativeSparkApiRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $creativeSparks = $this->creativeSparkApiService->update($id, $validatedData, $request->file('image'));
        $data = [
            'creative-sparks' => $creativeSparks
        ];
        return apiResponse(true, 'Update data successfully', $data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleted = $this->creativeSparkApiService->delete($id);
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
        $conds = [];
        if($request->has('title')) {
            $conds['title'] = $request->get('title');
        }
        return $conds;
    }
}
