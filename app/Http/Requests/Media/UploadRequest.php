<?php

namespace App\Http\Requests\Media;

use App\Models\Brand;
use App\Rules\ExtensionsRule;
use Illuminate\Foundation\Http\FormRequest;

class UploadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return (bool)(auth()->user()->brand ?? Brand::find($this->brandId));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $maxSize = 45 * 1024;
        return [
            'file' => ['file','max:'.$maxSize, new ExtensionsRule(['jpeg','jpg','pjpeg','eps','ai','mp4'])]
        ];
    }
}
