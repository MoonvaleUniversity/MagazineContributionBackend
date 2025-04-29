<?php

namespace Modules\CreativeSpark\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\Users\User\Services\UserApiServiceInterface;

class UpdateCreativeSparkApiRequest extends FormRequest
{
    public function __construct(protected UserApiServiceInterface $userApiService) {}

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
            "title" => "required|string",
            'image' => 'required|image',
            'content' => 'required|string'
        ];
    }
}
