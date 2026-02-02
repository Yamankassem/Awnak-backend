<?php

namespace Modules\Volunteers\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
/**
 * Class StoreVolunteerDocumentRequest
 *
 * Validates document uploads for volunteer profiles.
 *
 * @package Modules\Volunteers\Http\Requests
 */
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
     * Authorization check.
     *
     * Requires `profile.update.own` permission.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('profile.update.own');
    }
}
