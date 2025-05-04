<?php

namespace Modules\Users\Student\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Modules\Users\Coordinator\Services\CoordinatorApiServiceInterface;
use Modules\Users\Student\Services\StudentApiServiceInterface;

class UpdateStudentApiRequest extends FormRequest
{
    public function __construct(protected CoordinatorApiServiceInterface $coordinatorApiService, protected StudentApiServiceInterface $studentApiService) {}

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $userId = Auth::id();
        $coordinator = $this->coordinatorApiService->get($userId);
        if(!$coordinator) {
            return false;
        }

        $routeStudentId = $this->route('id');
        $student = $this->studentApiService->get($routeStudentId);
        if(!$student) {
            return false;
        }
        if($coordinator->faculty_id != $student->faculty_id) {
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
        $routeUserId = $this->route('student');

        return [
            'name' => 'required',
            'faculty_id' => 'nullable|exists:faculties,id',
            'password' => 'nullable|confirmed',
            'email' => [
                'required',
                Rule::unique('users', 'email')->ignore($routeUserId),
            ],
        ];
    }
}
