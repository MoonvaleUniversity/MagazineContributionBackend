<?php

namespace Modules\Contribution\App\Http\Requests;

use App\Enums\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\ClosureDate\Services\ClosureDateApiServiceInterface;
use Modules\Contribution\Services\ContributionApiServiceInterface;
use Modules\Users\User\Services\UserApiServiceInterface;

class UpdateContributionApiRequest extends FormRequest
{
    public function __construct(protected ClosureDateApiServiceInterface $closureDateApiService, protected UserApiServiceInterface $userApiService, protected ContributionApiServiceInterface $contributionApiService) {}

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $userId = Auth::user()->id;
        $user = $this->userApiService->get($userId);

        if (!$user->hasPermissionTo('contribution.edit', 'api')) {
            return false;
        }

        $contributionId = $this->route('contribution');
        $contribution = $this->contributionApiService->get($contributionId);

        if ($user->hasRole(Role::STUDENT->label())) {
            $closureDateId = $this->input('closure_date_id');

            if (!$closureDateId) {
                return false;
            }

            $closureDate = $this->closureDateApiService->get($closureDateId);

            if (!$closureDate) {
                return false;
            }

            if (now()->startOfDay()->greaterThan($closureDate->final_closure_date)) {
                return false;
            }
        } else {
            $this->merge([
                'closure_date_id' => $contribution->closure_date_id
            ]);
        }

        $this->merge([
            'user_id' => $contribution->user_id
        ]);

        $currentImageCount = $contribution->images()->count();
        $deleteCount = count((array) $this->input('delete_images', []));
        $newImagesCount = count((array) $this->file('images', []));
        $newTotalCount = ($currentImageCount - $deleteCount + $newImagesCount);

        if ($newTotalCount > 5) {
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
            'doc' => 'nullable|mimes:doc,docx',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg',
            'closure_date_id' => 'required|exists:closure_dates,id',
            'user_id' => 'required|exists:users,id',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'nullable|exists:contribution_images,id'
        ];
    }
}
