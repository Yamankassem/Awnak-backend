<?php

namespace Modules\Organizations\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Organizations\Http\Requests\DocumentRequest;
use Modules\Organizations\Models\Document;
use Modules\Organizations\Services\DocumentService;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Controller: DocumentController
 *
 * Handles CRUD operations for Document entities.
 * Delegates business logic (file storage, update, deletion) to DocumentService
 * for cleaner code, better testability, and maintainability.
 */
class DocumentController extends Controller
{
    protected DocumentService $documentService;

    public function __construct(DocumentService $documentService)
    {
        $this->documentService = $documentService;
    }

    /**
     * Retrieve all documents with their associated media.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $documents = $this->documentService->getAllDocuments();
        return response()->json($documents);
    }

    /**
     * Store a new document.
     *
     * Accepts `title`, `description`, and `file` from the request body.
     * Creates the document using the service and returns it with its media.
     *
     * @param DocumentRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(DocumentRequest $request): JsonResponse
    {
        $data = $request->validated();
        $document = $this->documentService->create($data);

        return response()->json([
            'message'  => 'Document uploaded successfully',
            'document' => $document->load('media')
        ], 201);
    }

    /**
     * Display a single document file by ID.
     *
     * @param Document $document
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function show(Document $document)
    {
        $media = $document->getFirstMedia('documents');
        return response()->file($media->getPath());

        // Use response()->download($media->getPath()) if download is preferred
    }

    /**
     * Update an existing document and replace its file if provided.
     *
     * @param DocumentRequest $request
     * @param Document $document
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(DocumentRequest $request, Document $document): JsonResponse
    {
        $document = $this->documentService->update($document, $request->validated());
        return response()->json([
            'message' => 'Document updated successfully',
            'document' => $document->load('media')
        ]);
    }

    /**
     * Delete a document and its associated media.
     *
     * @param Document $document
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Document $document): JsonResponse
    {
        $this->documentService->delete($document);
        return response()->json([
            'message' => 'Document deleted successfully'
        ]);
    }
}
