<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddPostTranslatePageRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
'title'     => [
                'required',
            ],
'language'  => [
                'nullable',
            ],
'summary'   => [
                'required',
            ],
'body'      => [
                'required',
            ],
'published' => [
                'integer',
            ],
];
    }
}
