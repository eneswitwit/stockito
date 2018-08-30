<?php

// namespace
namespace App\Http\Requests\Subscriptions;

// use
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpgradeRequest
 * @package App\Http\Requests\Subscriptions
 */
class SwapSubscriptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('api')->check() && auth('api')->user()->brand;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array();
    }

}