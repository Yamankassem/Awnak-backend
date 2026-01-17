<?php

namespace Modules\Organizations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * You can add authorization logic here if needed.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * These rules ensure that the incoming data is valid.
     */
    public function rules()
    {
        return [
            'title'          => 'required|string|max:255',
            'file_path'      => 'required|string|max:500',
            'file_type'      => 'required|string|max:50',
            'file_size'      => 'required|integer|min:1',
            'opportunity_id' => 'required|exists:opportunities,id',
        ];
    }

    /**
     * Custom error messages for validation rules.
     * This helps make validation feedback more user-friendly.
     */
    public function messages()
    {
        return [
            'title.required'        => 'The document title is required.',
            'file_path.required'    => 'The file path is required.',
            'file_type.required'    => 'The file type is required.',
            'file_size.required'    => 'The file size is required.',
            'opportunity_id.exists' => 'The selected opportunity does not exist.',
        ];
    }
}
