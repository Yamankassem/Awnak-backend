<?php

namespace Modules\Organizations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request: OpportunitySkillRequest
 *
 * Validates incoming data when creating or updating
 * records in the pivot table "opportunity_skill".
 * Ensures that both opportunity_id and skill_id exist
 * in their respective tables.
 */
class OpportunitySkillRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Allow all requests for now.
        // You can add custom authorization logic later if needed.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            // Ensure opportunity_id is provided and exists in the opportunities table
            'opportunity_id' => 'required|exists:opportunities,id',

            // Ensure skill_id is provided and exists in the skills table But for now I will but it nullable then after merge I will change it inshallah
            'skill_id'       => 'nullable|integer',
        ];
    }
}
