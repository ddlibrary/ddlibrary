<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostStepThreeResourceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
'translation_rights'     => [
                'integer',
            ],
'educational_resource'   => [
                'integer',
            ],
'copyright_holder'       => [
                'string',
                'nullable',
            ],
'iam_author'             => [
                'integer',
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
