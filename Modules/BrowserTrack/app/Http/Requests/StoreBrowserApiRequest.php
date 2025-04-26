<?php

namespace Modules\BrowserTrack\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\Users\Admin\Services\AdminApiServiceInterface;

class StoreBrowserApiRequest extends FormRequest
{
    public function __construct(protected AdminApiServiceInterface $adminApiService) {}
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        //     $userId = Auth::user()->id;
        //     $user = $this->adminApiService->get($userId);
        //     if ($user) {
        //         return true;
        //     }
        // return false;
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
            'browser_name' => 'required|string',
                'browser_version' => 'required|string',
                'os' => 'required|string',
        ];
    }
}
