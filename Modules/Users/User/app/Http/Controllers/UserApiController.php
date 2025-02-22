<?php

namespace Modules\Users\User\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Users\User\App\Http\Requests\StoreUserApiRequest;
use Modules\Users\User\App\Http\Requests\UpdateUserApiRequest;
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
        $users = $this->userApiService->getAll();
        $data = [
            'users' => UserApiResource::collection($users)
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
        $user = $this->userApiService->get($id);
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
        $user = $this->userApiService->update($id, $request->only('name'));
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
        $user = $this->userApiService->delete($id);
        $data = [
            'user' => new UserApiResource($user)
        ];
        return apiResponse(true, 'User deleted successfully', $data);
    }
}
