<?php

namespace Modules\Volunteers\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVolunteerDocumentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'max:5120'], // 5MB
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('profile.update.own');
    }
}
