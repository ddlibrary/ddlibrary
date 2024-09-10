<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePageRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
'title'     => [
                'required',
            ],
'language'  => [
                'required',
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
