<?php

namespace Modules\Users\Manager\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateManagerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $currentUserId = Auth::id();

        $routeMangerId = $this->route('id');

        // Prevent user from updating another coordinator's data
        if ((int) $currentUserId !== (int) $routeMangerId) {
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
        $routeMangerId = $this->route('id');

        return [
            'name' => 'required',
            'faculty_id' => 'required|exists:faculties,id',
            'password' => 'required|confirmed',
            'email' => "required|unique:users,email,$routeMangerId"
        ];
    }
}
