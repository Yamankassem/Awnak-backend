<?php

namespace Modules\Applications\Http\Requests\FeedbacksRequest;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Store PerformanceMetric Request
 * 
 * Validates performanceMetric creation requests.
 * 
 * @package Modules\Applications\Http\Requests\PerformanceMetricsRequest
 * @author Your Name
 */
class StorePerformanceMetricRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     * 
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|in:commitment,quality,collaboration,punctuality,initiative',
            'score' => 'required|integer|min:1|max:5',
            'notes' => 'nullable|string|max:500',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     * 
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }
}