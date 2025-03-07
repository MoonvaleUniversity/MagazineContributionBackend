<?php

namespace Modules\Faculty\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFacultyApiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
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
            "name" => "required|string",
            'image_url' => 'required|url',
        ];
    }
}
