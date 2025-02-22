<?php

namespace Modules\Authentication\App\Http\Controllers;

use App\Enums\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Authentication\Services\AuthenticationApiServiceInterface;
use Modules\Shared\Email\EmailServiceInterface;
use Modules\Users\User\App\Http\Requests\LoginApiRequest;
use Modules\Users\User\App\Http\Resources\UserApiResource;
use Modules\Users\User\Services\UserApiServiceInterface;

class AuthenticationApiController extends Controller
{
    public function __construct(protected AuthenticationApiServiceInterface $authenticationApiService, protected UserApiServiceInterface $userApiService, protected EmailServiceInterface $emailService) {}

    /**
     * Display a listing of the resource.
     */
    public function loginUser(Request $request)
    {
        $data = $request->user();
        return apiResponse(true, 'Data retrived successfully', $data);
    }

    // Admin will create users according to their suitable role
    public function register()
    {

    }

    // // Users login section
    public function login(LoginApiRequest $request)
    {
        $data = $request->validated();
        $user = $this->userApiService->get(conds: ['email' => $request->email]);
        $role = Role::tryFrom($request->role);

        if (!$user->email_verified_at) {
            return response()->json(['message' => "Need to verify first!"]);
        }
        if (!Auth::attempt($request->only('email', 'password')) || !$user->hasRole($role->label())) {
            return response()->json(["message" => "Invalid Credentials"]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        $data = ['user' => new UserApiResource($user), 'token' => $token];

        return apiResponse(true, "Login success.", $data);
    }

    public function verifyEmail($id)
    {
        return response()->json(['userId' => $id]);
    }

    // Mail sent for Verification
    public function verifyPost($id)
    {
        $user = $this->userApiService->get($id);
        if (!$user) {
            return response()->json(['status'  => 'error', 'message' => 'User not found'], 404);
        }

        $email = $user->email;
        $this->emailService->send('mail', $email, 'Email Verification', ['userId' => $user->id]);

        return apiResponse(true, 'Verification email sent successfully');
    }

    // Verify btn from mail and then route to confirmation page
    public function verificationPage($id)
    {
        $user = $this->userApiService->get($id);
        return response()->json(['userId' => $user->id]);
    }

    // Verify status change page
    public function verificationPost($id)
    {
        $user = $this->userApiService->get($id);
        if (!$user) {
            return response()->json(['status'  => 'error', 'message' => 'User not found'], 404);
        }
        if ($user->is_email_verify) {
            return response()->json(['status'  => 'error', 'message' => 'User is already verified']);
        }
        $user->update(['email_verified_at' => now()]);
        return response()->json(['status'  => 'success', 'message' => 'Verified successfully']);
    }
}
