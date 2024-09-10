<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostStepOneEditResourceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
'title'      => 'required',
'author'     => 'string|nullable',
'publisher'  => 'string|nullable',
'translator' => 'string|nullable',
'language'   => 'required',
'abstract'   => 'required',
];
    }
}
