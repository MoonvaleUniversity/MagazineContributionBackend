<?php

namespace Modules\AcademicYear\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\Users\User\Services\UserApiServiceInterface;

class ViewAcademicYearApiRequest extends FormRequest
{
    public function __construct(protected UserApiServiceInterface $userApiService) {}
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $userId = Auth::user()->id;
        $user = $this->userApiService->get($userId);
        if ($user->hasPermissionTo('academic-year.view', 'api')) {
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
