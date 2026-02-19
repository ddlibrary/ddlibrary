<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class TaxonomyTermListRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules for the taxonomy list filters.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $supportedLocales = array_keys(LaravelLocalization::getSupportedLocales());

        return [
            'taxonomy_vocabulary_id' => ['sometimes', 'required', 'exists:taxonomy_vocabulary,vid'],
            'term' => ['nullable', 'string', 'max:255'],
            'language' => ['sometimes', 'required', 'string', Rule::in($supportedLocales)],
        ];
    }
}
