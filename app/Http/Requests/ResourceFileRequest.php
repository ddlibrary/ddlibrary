<?php

namespace App\Http\Requests;

use App\Enums\LanguageEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ResourceFileRequest extends FormRequest
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
            'search' => ['nullable', 'string'],
            'subject_area_id' => ['nullable', 'numeric'],
            'language' => [
                'required',
                Rule::in($this->getAvailableLanguages())
            ]
        ];
    }

    /**
     * Get available languages from the Enum.
     *
     * @return array<string>
     */
    private function getAvailableLanguages(): array
    {
        return array_map(fn($lang) => $lang->value, LanguageEnum::cases());
    }
}
