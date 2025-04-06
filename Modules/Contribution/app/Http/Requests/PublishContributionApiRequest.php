<?php

namespace Modules\Contribution\App\Http\Requests;

use App\Enums\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\Contribution\Services\ContributionApiServiceInterface;
use Modules\Users\User\Services\UserApiServiceInterface;

class PublishContributionApiRequest extends FormRequest
{
    public function __construct(protected UserApiServiceInterface $userApiService, protected ContributionApiServiceInterface $contributionApiService) {}
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $userId = Auth::id();
        $user = $this->userApiService->get($userId, conds: ['role' => Role::MARKETING_COORDINATOR->label()]);
        if (!$user || !$user->hasPermissionTo('contribution.edit', 'api')) {
            return false;
        }
        if (!$this->contributionApiService->get($this->route('contribution'))) {
            return false;
        }
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'comment' => 'required'
        ];
    }
}
