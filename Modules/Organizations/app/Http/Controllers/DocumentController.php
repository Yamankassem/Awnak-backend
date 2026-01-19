<?php

namespace Modules\Organizations\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Organizations\Http\Requests\DocumentRequest;
use Modules\Organizations\Models\Document;
use Modules\Organizations\Transformers\DocumentResource;
use Modules\Organizations\Services\DocumentService;

/**
 * Controller: DocumentController
 *
 * Handles CRUD operations for Document entities.
 * Delegates business logic (file storage, update, deletion) to DocumentService
 * for cleaner code and better maintainability.
 */
class DocumentController extends Controller
{
    protected DocumentService $documentService;

    public function __construct(DocumentService $documentService)
    {
        $this->documentService = $documentService;
    }

    /**
     * Display all documents (across all opportunities).
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $documents = Document::all();
        return DocumentResource::collection($documents);
    }

    /**
     * Store: Create a new document.
     *
     * Accepts `title`, `file_path`, `file_type`, `file_size`, and `opportunity_id`
     * from the request body. Creates the document using the service and returns
     * it wrapped in a resource.
     *
     * @param DocumentRequest $request
     * @return DocumentResource
     */
    public function store(DocumentRequest $request)
    {
        $data = $request->validated();
        $document = $this->documentService->create($data);

        return new DocumentResource($document);
    }



    /**
     * Show a single document by ID.
     *
     * @param Document $document
     * @return DocumentResource
     */
    public function show(Document $document)
    {
        return new DocumentResource($document);
    }



    /**
     * Update an existing document.
     *
     * @param DocumentRequest $request
     * @param Document $document
     * @return DocumentResource
     */
    public function update(DocumentRequest $request, Document $document)
    {
        $document = $this->documentService->update($document, $request->validated());
        return new DocumentResource($document);
    }

    

    /**
     * Delete a document.
     *
     * @param Document $document
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Document $document)
    {
        $this->documentService->delete($document);
        return response()->json(['message' => 'Document deleted successfully']);
    }
}
