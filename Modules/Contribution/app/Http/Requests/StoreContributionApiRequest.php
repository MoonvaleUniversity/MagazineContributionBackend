<?php

namespace Modules\Contribution\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\ClosureDate\Services\ClosureDateApiServiceInterface;
use Modules\Users\User\Services\UserApiServiceInterface;

class  StoreContributionApiRequest extends FormRequest
{
    public function __construct(protected ClosureDateApiServiceInterface $closureDateApiService, protected UserApiServiceInterface $userApiService) {}

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $userId = Auth::user()->id;
        $user = $this->userApiService->get($userId);
        if (!$user->hasPermissionTo('contribution.create', 'api')) {
            return false;
        }

        $closureDateId = $this->input('closure_date_id');

        if (!$closureDateId) {
            return false;
        }

        $closureDate = $this->closureDateApiService->get($closureDateId);

        if (!$closureDate) {
            return false;
        }

        if (now()->startOfDay()->greaterThan($closureDate->closure_date)) {
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
            'name'  => 'required',
            'doc' => 'required|mimes:doc,docx',
            'images' => 'required|array',
            'images.*' => 'required|image|mimes:jpeg,png,jpg',
            'closure_date_id' => 'required|exists:closure_dates,id',
            'user_id' => 'required|exists:users,id'
        ];
    }
}
