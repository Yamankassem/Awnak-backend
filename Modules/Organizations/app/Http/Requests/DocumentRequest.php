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
        return [
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string|max:1000',
            'file'           => 'required|file|mimes:pdf,docx,txt|max:10240',
            'opportunity_id' => 'required|exists:opportunities,id',
        ];
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
