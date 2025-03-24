<?php

namespace Modules\Users\Student\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateStudentApiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $currentUserId = Auth::id();

        $routeCoordinatorId = $this->route('id');

        // Prevent user from updating another coordinator's data
        if ((int) $currentUserId !== (int) $routeCoordinatorId) {
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
        $routeCoordinatorId = $this->route('id');

        return [
            'name' => 'required',
            'faculty_id' => 'required|exists:faculties,id',
            'password' => 'required|confirmed',
            'email' => "required|unique:users,email,$routeCoordinatorId"
        ];
    }
}
