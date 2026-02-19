<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TaxonomyTermRequest extends FormRequest
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
            'vid' => 'required|exists:taxonomy_vocabulary,vid',
            'weight' => 'required|integer|min:0',
            'names' => 'required|array',
            'names.*' => 'nullable|string|max:255',
            'parents' => 'nullable|array',
            'parents.*' => 'nullable|integer|min:0',
            'term_ids' => 'nullable|array',
            'term_ids.*' => 'nullable|integer|exists:taxonomy_term_data,id',
        ];
    }

    /**
     * Require at least one non-empty translation name.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $names = $this->input('names', []);
            $trimmed = array_map(fn ($v) => is_string($v) ? trim($v) : $v, $names);
            $filled = array_filter($trimmed);
            if (empty($filled)) {
                $validator->errors()->add('names', __('At least one translation name is required.'));
            }
        });
    }
}
