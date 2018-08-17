<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ExtensionsRule implements Rule
{
    /**
     * @var array
     */
    protected $extensions;

    /**
     * ExtensionsRule constructor.
     * @param $extensions
     */
    public function __construct($extensions)
    {
        $this->extensions = $extensions;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return $value === null || \in_array(strtolower($value->getClientOriginalExtension()), $this->extensions);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The file should has only '.implode(',', $this->extensions).' extensions';
    }
}
