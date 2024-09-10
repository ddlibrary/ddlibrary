<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGlossarySubjectRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
'english'   => [
                'required',
            ],
'farsi'     => [
                'required',
            ],
'pashto'    => [
                'required',
            ],
'munji'     => [
                'required',
            ],
'nuristani' => [
                'required',
            ],
'pashayi'   => [
                'required',
            ],
'shughni'   => [
                'required',
            ],
'swahili'   => [
                'required',
            ],
'uzbek'     => [
                'required',
            ],
'id'        => [
                'required',
            ],
];
    }
}
