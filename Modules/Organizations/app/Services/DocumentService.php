<?php

namespace Modules\Organizations\Services;

use Modules\Organizations\Models\Document;
use Illuminate\Support\Facades\Storage;

/**
 * Service: DocumentService
 *
 * This service class encapsulates the business logic related to
 * creating, updating, and deleting documents. By separating this logic
 * from the controller, we achieve cleaner code, better testability,
 * and easier maintenance.
 */
class DocumentService
{
    /**
     * Create a new document and store its file.
     *
     * @param array $data Validated document data
     * @return Document Newly created document instance
     */
    public function create(array $data): Document
    {
        // Handle file upload if provided
        if (isset($data['file'])) {
            $path = Storage::putFile('documents', $data['file']);
            $data['path'] = $path;
        }

        // Create and return the document
        return Document::create($data);
    }

    /**
     * Update an existing document.
     *
     * @param Document $document The document instance to update
     * @param array $data Validated document data
     * @return Document Updated document instance
     */
    public function update(Document $document, array $data): Document
    {
        // Handle file replacement if a new file is provided
        if (isset($data['file'])) {
            // Delete old file if exists
            if ($document->path && Storage::exists($document->path)) {
                Storage::delete($document->path);
            }

            // Store new file
            $path = Storage::putFile('documents', $data['file']);
            $data['path'] = $path;
        }

        // Apply updates to the document model
        $document->update($data);

        return $document;
    }

    /**
     * Delete a document and its file.
     *
     * @param Document $document The document instance to delete
     * @return bool True if deletion was successful
     */
    public function delete(Document $document): bool
    {
        // Delete file from storage if exists
        if ($document->path && Storage::exists($document->path)) {
            Storage::delete($document->path);
        }

        // Delete document record
        return $document->delete();
    }
}
