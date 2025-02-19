<?php

namespace Modules\Users\User\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Users\User\App\Http\Resources\UserApiResource;
use Modules\Users\User\Services\UserApiServiceInterface;

class UserApiController extends Controller
{
    public function __construct(protected UserApiServiceInterface $userApiService) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = $this->userApiService->getAll(role: 'Admin');
        $data = UserApiResource::collection($users);
        return apiResponse(true, 'Data retrived successfully', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
