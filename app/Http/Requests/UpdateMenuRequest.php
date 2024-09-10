<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMenuRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
'title'    => [
                'required',
            ],
'location' => [
                'required',
            ],
'path'     => [
                'required',
            ],
'parent'   => [
                'nullable',
            ],
'status'   => [
                'required',
            ],
'language' => [
                'required',
            ],
'weight'   => [
                'required',
            ],
];
    }
}
