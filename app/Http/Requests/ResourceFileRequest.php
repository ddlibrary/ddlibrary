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
            'language' => ['required', Rule::in([
                LanguageEnum::Farsi, LanguageEnum::Pashto, LanguageEnum::Munji, 
                LanguageEnum::Noorestani, LanguageEnum::Pashaiee,
                LanguageEnum::Shaghnani, LanguageEnum::Sowji, 
                LanguageEnum::Uzbaki, LanguageEnum::English
            ])]
        ];
    }
}
