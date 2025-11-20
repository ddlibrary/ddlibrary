<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResourceStepOneRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
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
            'title' => 'required',
            'author' => 'string|nullable',
            'publisher' => 'string|nullable',
            'has_translator' => 'nullable',
            'translator' => 'required_if:has_translator,1|string|nullable',
            'language' => 'required',
            'abstract' => 'required',
            'resource_file_id' => 'nullable|numeric',
        ];
    }
}
