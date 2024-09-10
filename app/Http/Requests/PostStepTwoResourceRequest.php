<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostStepTwoResourceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'attachments.*' => [
                'file',
                'mimes:xlsx,xls,csv,jpg,jpeg,png,bmp,mpga,ppt,pptx,doc,docx,pdf,tif,tiff,mp3',
                'max:131072',
            ],
            // Max file size is 128 MB
            'subject_areas' => [
                'required',
            ],
            'keywords' => [
                'string',
                'nullable',
            ],
            'learning_resources_types' => [
                'required',
            ],
            'educational_use' => [
                'required',
            ],
            'level' => [
                'required',
            ],
        ];
    }
}
