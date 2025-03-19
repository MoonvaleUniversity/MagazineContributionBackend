<?php
namespace Modules\Authentication\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Modules\Authentication\App\Http\Requests\LoginApiRequest;
use Modules\Authentication\Services\AuthenticationApiServiceInterface;
use Modules\Shared\Email\EmailServiceInterface;
use Modules\Users\User\App\Http\Resources\UserApiResource;
use Modules\Users\User\App\Models\User;
use Modules\Users\User\Services\UserApiServiceInterface;

class AuthenticationApiController extends Controller
{
    public function __construct(protected AuthenticationApiServiceInterface $authenticationApiService, protected UserApiServiceInterface $userApiService, protected EmailServiceInterface $emailService)
    {}

    /**
     * Display a listing of the resource.
     */
    public function loginUser(Request $request)
    {
        $data = User::all();
        return apiResponse(true, 'Data retrived successfully', $data);
    }

    // Admin will create users according to their suitable role
    public function register(){}

    // // Users login section
    public function login(LoginApiRequest $request)
    {
        $data = $request->validated();
        $user = $this->userApiService->get(conds: ['email' => $request->email]);

        if (! $user->email_verified_at) {
            return apiResponse(false, 'You need to verify your email first.', statusCode: 401, errors: ['email' => ['You need to verify your email first.']]);
        }
        if (! Auth::guard('web')->attempt($request->only('email', 'password'))) {
            return apiResponse(false, 'Invaliad Credentials.', statusCode: 401, errors: ['credentials' => ['The credentials you provided is incorrect.']]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        $data  = ['user' => new UserApiResource($user), 'token' => $token];

        return apiResponse(true, "Login success.", $data);
    }

    public function verifyEmail($id)
    {
        return response()->json(['userId' => $id]);
    }

    // Mail sent for Verification
    public function verifyPost($email)
    {
        $user = $this->userApiService->get(conds: ['email' => $email]);
        if (! $user) {
            return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
        }
        $signedUrl = URL::temporarySignedRoute('verificationPage', now()->addMinutes(30), ['id' => $user->id]);

        $this->emailService->send('mail', $email, 'Email Verification', ['signedUrl' => $signedUrl]);

        return apiResponse(true, 'Verification email sent successfully');
    }

    // Verify btn from mail and then route to confirmation page
    public function verificationPage($id)
    {
        $user = $this->userApiService->get($id);
        $this->userApiService->update($id, ['email_verified_at' => now()]);

        return view('email-verified');
    }

    // Verify status change page
    public function verificationPost($id)
    {
        $user = $this->userApiService->get($id);
        if (! $user) {
            return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
        }
        if ($user->is_email_verify) {
            return response()->json(['status' => 'error', 'message' => 'User is already verified']);
        }
        $user->update(['email_verified_at' => now()]);
        return response()->json(['status' => 'success', 'message' => 'Verified successfully']);
    }
}
