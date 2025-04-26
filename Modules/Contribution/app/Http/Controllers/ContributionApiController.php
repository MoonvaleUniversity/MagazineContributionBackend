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
    protected $contributionApiRelations;
    public function __construct(protected ContributionApiServiceInterface $contributionApiService)
    {
        $this->contributionApiRelations = ['images'];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        [$limit, $offset] = getLimitOffsetFromRequest($request);
        [$noPagination, $pagPerPage] = getNoPaginationPagPerPageFromRequest($request);
        $conds = $this->getFilterConditions($request);

        $contribution = $this->contributionApiService->getAll($this->contributionApiRelations, $limit, $offset, $noPagination, $pagPerPage, $conds);
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
        $contribution = $this->contributionApiService->get($id);
        $data = [
            'contribution_id' => $contribution
        ];
        return apiResponse(true, "Data Retrieve successfully", $data);
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
            'user_id' => $request->user_id,
            'user_id@@name' => $request->user_name,
            'user_id@@academic_year_id' => $request->user_academic_year_id,
            'closure_date_id' => $request->closure_date_id,
            'closure_date_id@@academic_year_id' => $request->closure_date_academic_year_id,
            'is_selected_for_publication' => $request->is_selected_for_publication
        ];
    }
}
