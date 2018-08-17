<?php

namespace App\Http\Requests\Media;

use App\Models\Share;
use Illuminate\Foundation\Http\FormRequest;

class ShareRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
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
            'type' => 'required|in:'.implode(',', Share::getTypes()),
            'media' => 'required',
            'days' => 'required_if:type,'.Share::TIME_LIMITED_LINK,
        ];
    }
}
