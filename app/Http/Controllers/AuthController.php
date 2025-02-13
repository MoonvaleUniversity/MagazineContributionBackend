<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRoleRequest;
use App\Http\Requests\UserStoreRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
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
        $data['password']=Hash::make($data['password']);
        $user = User::create($data);
        if (!$user) {
            return response()->json(['users' => $user, 'error_message' => 'Please fill the data correctly']);
        }
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json(['users' => $user, 'tokens' => $token, 'message' => 'Register Successfully']);
    }

    // Users login section
    public function login(UserRoleRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (! $user || $request->role !== $user->role || ! Hash::check($request->password, $user->password)) {
            return response()->json(["message" => "Invalid Credentials"]);
        }
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json(['token' => $token, 'user' => $user]);
    }
}
