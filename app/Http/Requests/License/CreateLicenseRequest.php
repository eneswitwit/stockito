<?php

// namespace
namespace App\Http\Requests\License;

// use
use App\Models\Brand;
use App\Models\License;
use App\Rules\ExtensionsRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

/**
 * Class CreateLicenseRequest
 *
 * @package App\Http\Requests\License
 */
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
            'startDate' => 'required_if:type,'.implode(',', [License::RM, License::RE, License::BO]),
            'expireDate' => 'required_if:type,'.implode(',', [License::RM, License::RE, License::BO]),
            'billFile' => [new ExtensionsRule(['pdf'])]
        ];
    }
}
