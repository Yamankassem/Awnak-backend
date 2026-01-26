<?

namespace Modules\Organizations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request: DocumentRequest
 *
 * Validates incoming requests for creating or updating documents.
 * Ensures that required fields are present and files meet type/size constraints.
 */
class DocumentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Adjust authorization logic as needed (e.g., user roles, permissions).
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Define validation rules for document requests.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'title'          => 'required|string|max:255',
            'file'           => 'required|file|mimes:pdf,docx,txt|max:10240',
            'opportunity_id' => 'required|exists:opportunities,id',
        ];
    }

    /**
     * Custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required'         => 'The document title is required.',
            'file.required'          => 'A file must be uploaded.',
            'file.mimes'             => 'The file must be a PDF, DOCX, or TXT.',
            'file.max'               => 'The file size may not exceed 10 MB.',
            'opportunity_id.required'=> 'An opportunity ID is required.',
            'opportunity_id.exists'  => 'The selected opportunity does not exist.',
        ];
    }
}
