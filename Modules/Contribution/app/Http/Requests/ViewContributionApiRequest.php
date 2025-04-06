<?php

namespace Modules\Contribution\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\Users\User\Services\UserApiServiceInterface;

class ViewContributionApiRequest extends FormRequest
{
    public function __construct(protected UserApiServiceInterface $userApiService) {}
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $userId = Auth::user()->id;
        $user = $this->userApiService->get($userId);
        if ($user->hasPermissionTo('contribution.view', 'api')) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [];
    }
}
