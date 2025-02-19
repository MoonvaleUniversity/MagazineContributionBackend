<?php

namespace Modules\Authentication\App\Http\Controllers;

use Modules\Users\User\App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\UserRoleRequest;
use App\Http\Requests\UserStoreRequest;
use Modules\Authentication\Services\AuthenticationApiServiceInterface;
use Modules\Users\User\Services\UserApiServiceInterface;

class AuthenticationController extends Controller
{
    public function __construct(protected AuthenticationApiServiceInterface $authenticationApiService, protected UserApiServiceInterface $userApiService) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::get()->all();
        return response()->json([
            'users' => $user
        ]);
    }

    // Admin will create users according to their suitable role
    public function register(UserStoreRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        if (!$user) {
            return response()->json(['users' => $user, 'error_message' => 'Please fill the data correctly']);
        }
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json(['users' => $user, 'tokens' => $token, 'message' => 'Register Successfully']);
    }

    // // Users login section
    public function login(UserRoleRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user->is_email_verify != true) {
            return response()->json(['message' => "Need to verify first!"]);
        }
        if (! $user || $request->role !== $user->role || ! Hash::check($request->password, $user->password)) {
            return response()->json(["message" => "Invalid Credentials"]);
        }
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json(['token' => $token, 'user' => $user]);
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
        Mail::send('mail', ['userId' => $user->id], function ($message) use ($email) {
            $message->to($email)
                ->subject('Email Verification');
        });
        return response()->json(['status'  => 'success', 'message' => 'Verification email sent successfully']);
    }

    // Verify btn from mail and then route to confirmation page
    public function verificationPage($id)
    {
        $user = $this->userApiService->get($id);
        //  return view('verificationPage', ['userId' => $user->id]);
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
        $user->update(['is_email_verify' => true, 'email_verified_at' => now()]);
        return response()->json(['status'  => 'success', 'message' => 'Verified successfully']);
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
