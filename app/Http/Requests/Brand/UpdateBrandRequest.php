<?php

// namespace
namespace App\Http\Requests\Brand;

// use
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateBrandRequest
 *
 * @package App\Http\Requests\Brand
 */
class UpdateBrandRequest extends FormRequest
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
            'brand_name' => 'required',
            'company_name' => 'required',
            'email' => 'required|email',
            'address_1' => 'required',
            'address_2' => 'string|max:255|nullable',
            'city' => 'required',
            'zip' => 'required',
            'country_id' => 'required',
            'eur_uid' => 'required',
            'homepage' => 'required',
            'phone' => 'required',
            'contact_first_name' => 'required',
            'contact_last_name' => 'required',
            'contact_title' => 'string|max:255|nullable',
        ];
    }
}
