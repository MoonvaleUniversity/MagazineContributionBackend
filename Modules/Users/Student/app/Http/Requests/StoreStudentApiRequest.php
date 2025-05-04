<?php

namespace Modules\Users\Student\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\Users\Coordinator\Services\CoordinatorApiServiceInterface;

class StoreStudentApiRequest extends FormRequest
{
    public function __construct(protected CoordinatorApiServiceInterface $coordinatorApiService) {}
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $userId = Auth::user()->id;
        $coordinators = $this->coordinatorApiService->get($userId);
        if (!$coordinators) {
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
            'academic_year_id' => 'required|exists:academic_years,id',
            'password' => 'required|confirmed',
            'email' => 'required|unique:users,email'
        ];
    }
}
