<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGlossaryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
'english' => 'required_without_all:farsi,pashto',
'farsi'   => 'required_without_all:english,pashto',
'pashto'  => 'required_without_all:farsi,english',
'subject' => 'required',
];
    }
}
