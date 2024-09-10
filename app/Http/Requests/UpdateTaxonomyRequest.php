<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaxonomyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
'vid'      => [
                'required',
            ],
'name'     => [
                'required',
            ],
'weight'   => [
                'required',
            ],
'language' => [
                'required',
            ],
];
    }
}
