<?php

namespace App\Http\Requests\License;

use App\Models\License;
use App\Rules\ExtensionsRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLicenseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'id' => 'required|exists:licenses,id',
            'type' => 'required|in:'.implode(',', License::getLicenses()),
//            'usage' => 'required_if:type,'.License::RM,
//            'printrun' => 'required_if:type,'.implode(',', [License::RM, License::RF]),
//            'anyLimitations' => 'required_if:type,'.License::BO,
            'startDate' => 'required_if:type,'.implode(',', [License::RM, License::RE, License::BO]),
            'expireDate' => 'required_if:type,'.implode(',', [License::RM, License::RE, License::BO]),
//            'territory' => 'required_if:type,'.License::RE,
//            'invoiceNumber' => 'required',
//            'invoiceNumberBy' => 'required',
            'billFile' => [new ExtensionsRule(['pdf'])],
        ];
    }
}
