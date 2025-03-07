<?php

namespace Modules\Contribution\App\Http\Controllers;

use Exception;
use Mockery\Expectation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Contribution\App\Http\Requests\StoreContributionApiRequest;
use Modules\Contribution\Services\ContributionApiServiceInterface;

class ContributionApiController extends Controller
{
    public function __construct(protected ContributionApiServiceInterface $contributionApiService) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contribution = $this->contributionApiService->getAll();
        $data = [
            'contributions' => $contribution
        ];
        return apiResponse(true, 'Data retrieve successfully', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContributionApiRequest $request)
    {
        $validatedData = $request->validated();

        $contribution = $this->contributionApiService->create($validatedData, $request->file('doc'), $request->file('images'));
        $data = [
            'contributions' => $contribution
        ];
        return apiResponse(true, 'Contribution stored successfully', $data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
