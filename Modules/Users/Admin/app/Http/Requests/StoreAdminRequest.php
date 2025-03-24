<?php

namespace Modules\Users\Admin\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\Users\Admin\Services\AdminApiServiceInterface;

class StoreAdminRequest extends FormRequest
{
    public function __construct(protected AdminApiServiceInterface $adminApiService) {}
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $userId = Auth::user()->id;
        $admin = $this->adminApiService->get($userId);
        if (!$admin) {
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
            'name' => 'required',
            'faculty_id' => 'required|exists:faculties,id',
            'password' => 'required|confirmed',
            'email' => 'required|unique:users,email'
        ];
    }
}
