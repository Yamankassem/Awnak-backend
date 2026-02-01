<?php

namespace Modules\Organizations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
{
    $rules = [
        'title'          => $this->isMethod('post') ? 'required|string|max:255' : 'sometimes|string|max:255',
        'description'    => 'nullable|string|max:1000',
        'opportunity_id' => $this->isMethod('post') ? 'required|exists:opportunities,id' : 'sometimes|exists:opportunities,id',
    ];

    if ($this->isMethod('post')) {
        $rules['file'] = 'required|file|mimes:pdf,docx,txt|max:10240';
    }

    if ($this->isMethod('put') || $this->isMethod('patch')) {
        $rules['file'] = 'nullable|file|mimes:pdf,docx,txt|max:10240';
    }

    return $rules;
}




    public function messages(): array
    {
        return [
            'title.required'          => 'The document title is required.',
            'file.required'           => 'A file must be uploaded.',
            'file.file'               => 'The uploaded input must be a valid file.',
            'file.mimes'              => 'The file must be a PDF, DOCX, or TXT.',
            'file.max'                => 'The file size may not exceed 10 MB.',
            'opportunity_id.required' => 'An opportunity ID is required.',
            'opportunity_id.exists'   => 'The selected opportunity does not exist.',
        ];
    }
}
