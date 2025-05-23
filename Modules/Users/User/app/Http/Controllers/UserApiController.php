<?php

namespace Modules\Users\User\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Contribution\Services\ContributionApiServiceInterface;
use Modules\Users\User\App\Http\Requests\StoreUserApiRequest;
use Modules\Users\User\App\Http\Requests\UpdateUserApiRequest;
use Modules\Users\User\App\Http\Resources\UserApiResource;
use Modules\Users\User\Services\UserApiServiceInterface;

class UserApiController extends Controller
{
    protected $userApiRelations = ['saved_contributions','saved_articles'];

    public function __construct(protected UserApiServiceInterface $userApiService, protected ContributionApiServiceInterface $contributionApiService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        [$limit, $offset] = getLimitOffsetFromRequest($request);
        [$noPagination, $pagPerPage] = getNoPaginationPagPerPageFromRequest($request);
        $conds = $this->getFilterConditions($request);

        $users = $this->userApiService->getAll($this->userApiRelations, $limit, $offset, $noPagination, $pagPerPage, $conds);
        $data = [
            'users' => boolval($noPagination) || boolval($pagPerPage) ? UserApiResource::collection($users) : UserApiResource::collection($users)->response()->getData(true)
        ];
        return apiResponse(true, 'Data retrived successfully', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserApiRequest $request)
    {
        $validatedData = $request->validated();
        $user = $this->userApiService->create($validatedData);
        $data = [
            'user' => new UserApiResource($user)
        ];
        return apiResponse(true, 'User created successfully', $data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = $this->userApiService->get($id, $this->userApiRelations);
        $data = [
            'user' => new UserApiResource($user)
        ];
        return apiResponse(true, 'Data retrived successfully', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserApiRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $user = $this->userApiService->update($id, $validatedData);
        $data = [
            'user' => new UserApiResource($user)
        ];
        return apiResponse(true, 'User updated successfully', $data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = $this->userApiService->get($id, ['contributions']);
        foreach($user->contributions as $contribution) {
            $this->contributionApiService->delete($contribution->id);
        }
        $name = $this->userApiService->delete($id);
        $data = [
            'name' => $name
        ];
        return apiResponse(true, 'User deleted successfully', $data);
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
            'role' => $request->role,
            'email' => $request->email,
            'academic_year_id' => $request->academic_year_id,
            'faculty_id' => $request->faculty_id
        ];
    }
}
