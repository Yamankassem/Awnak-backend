<?

namespace Modules\Organizations\Services;

use Modules\Organizations\Models\Document;
/**
 * Service: DocumentService
 *
 * This service class encapsulates the business logic for managing documents,
 * including creation, updating, deletion, and retrieval. By separating this
 * logic from the controller, we achieve cleaner code, better testability,
 * and easier maintenance.
 */
class DocumentService
{
    /**
     * Create a new document and attach its file if provided.
     *
     * @param array $data Validated document data (title, description, file)
     * @return Document Newly created document instance
     */
    public function create(array $data): Document
    {
        $document = Document::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
        ]);

        if (!empty($data['file'])) {
            $document->addMedia($data['file'])->toMediaCollection('documents');
        }

        return $document;
    }

    /**
     * Update an existing document and replace its file if a new one is provided.
     *
     * @param Document $document The document instance to update
     * @param array $data Validated document data (title, description, file)
     * @return Document Updated document instance
     */
    public function update(Document $document, array $data): Document
    {
        $document->update($data);

        if (!empty($data['file'])) {
            $document->clearMediaCollection('documents');
            $document->addMedia($data['file'])->toMediaCollection('documents');
        }

        return $document;
    }

    /**
     * Delete a document and its associated media files.
     *
     * @param Document $document The document instance to delete
     * @return bool True if deletion was successful
     */
    public function delete(Document $document): bool
    {
        $document->clearMediaCollection('documents');
        return $document->delete();
    }

    /**
     * Retrieve all documents with their associated media.
     *
     * @return \Illuminate\Database\Eloquent\Collection Collection of documents with media
     */
    public function getAllDocuments()
    {
        return Document::with('media')->latest()->get();
    }
}
