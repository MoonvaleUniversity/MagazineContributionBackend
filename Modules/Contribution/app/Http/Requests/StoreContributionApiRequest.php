<?php

namespace Modules\Contribution\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\ClosureDate\Services\ClosureDateApiServiceInterface;

class StoreContributionApiRequest extends FormRequest
{
    public function __construct(protected ClosureDateApiServiceInterface $closureDateApiService) {}

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $closureDateId = $this->input('closure_date_id');

        if (!$closureDateId) {
            return false;
        }

        $closureDate = $this->closureDateApiService->get($closureDateId);

        if (!$closureDate) {
            return false;
        }

        if (now()->startOfDay()->greaterThan($closureDate->closure_date)) {
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
            'doc' => 'required|mimes:doc,docx',
            'images' => 'required|array',
            'images.*' => 'required|image|mimes:jpeg,png,jpg',
            'closure_date_id' => 'required|exists:closure_dates,id',
            'user_id' => 'required|exists:users,id'
        ];
    }
}
