<?php

namespace App\Http\Requests\License;

use App\Models\Brand;
use App\Models\License;
use App\Rules\ExtensionsRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CreateLicenseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @param Request $request
     *
     * @return bool
     */
    public function authorize(Request $request): bool
    {
        $brand = $this->user()->brand;
        if (!$brand && $request->get('selectedBrand')) {
            $brand = Brand::find($request->get('selectedBrand'));
        }

        return (bool)$brand;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'type' => 'required|in:'.implode(',', License::getLicenses()),
//            'usage' => 'required_if:type,'.License::RM,
//            'printrun' => 'required_if:type,'.implode(',', [License::RM, License::RF]),
//            'anyLimitations' => 'required_if:type,'.License::BO,
            'startDate' => 'required_if:type,'.implode(',', [License::RM, License::RE, License::BO]),
            'expireDate' => 'required_if:type,'.implode(',', [License::RM, License::RE, License::BO]),
//            'territory' => 'required_if:type,'.License::RE,
//            'invoiceNumber' => 'required',
//            'invoiceNumberBy' => 'required',
//            'billFile' => 'mimes:pdf',
            'billFile' => [new ExtensionsRule(['pdf'])],
            'mediaId' => 'required'
        ];
    }
}
