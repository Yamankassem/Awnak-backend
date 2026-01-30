<?php

namespace Modules\Evaluations\Http\Requests\Report;

use Illuminate\Foundation\Http\FormRequest;

class StoreReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'report_type' => 'required|string|max:100',
            'param'       => 'nullable|array',
            'url'         => 'required|string',
        ];
    }
}
