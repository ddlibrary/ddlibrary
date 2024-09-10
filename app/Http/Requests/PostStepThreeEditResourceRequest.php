<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostStepThreeEditResourceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
'translation_rights'     => [
                'integer',
            ],
'educational_resource'   => [
                'integer',
            ],
'iam_author'             => [
                'integer',
            ],
'copyright_holder'       => [
                'string',
                'nullable',
            ],
'creative_commons'       => [
                'integer',
            ],
'creative_commons_other' => [
                'integer',
            ],
];
    }
}
