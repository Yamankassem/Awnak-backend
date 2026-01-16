<?php

namespace Modules\Organizations\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Organizations\Http\Requests\DocumentRequest;
use Modules\Organizations\Models\Document;
use Modules\Organizations\Http\Resources\DocumentResource;
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

    /**
     * Inject the DocumentService into the controller.
     */
    public function __construct(DocumentService $documentService)
    {
        $this->documentService = $documentService;
    }

    /**
     * Display a listing of documents for a specific opportunity.
     *
     * @param int $opportunityId
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index($opportunityId)
    {
        // Retrieve all documents for the given opportunity
        $documents = Document::where('opportunity_id', $opportunityId)->get();

        // Return as a collection of DocumentResource
        return DocumentResource::collection($documents);
    }

    /**
     * Store a newly created document for an opportunity using the service.
     *
     * @param DocumentRequest $request
     * @param int $opportunityId
     * @return DocumentResource
     */
    public function store(DocumentRequest $request, $opportunityId)
    {
        // Merge opportunity_id into validated data
        $data = $request->validated();
        $data['opportunity_id'] = $opportunityId;

        // Create document using the service
        $document = $this->documentService->create($data);

        // Return the newly created document wrapped in a resource
        return new DocumentResource($document);
    }

    /**
     * Update an existing document using the service.
     *
     * @param DocumentRequest $request
     * @param Document $document
     * @return DocumentResource
     */
    public function update(DocumentRequest $request, Document $document)
    {
        // Update document using the service with validated request data
        $document = $this->documentService->update($document, $request->validated());

        // Return updated document wrapped in a resource
        return new DocumentResource($document);
    }

    /**
     * Remove the specified document using the service.
     *
     * @param Document $document
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Document $document)
    {
        // Delete document using the service
        $this->documentService->delete($document);

        // Return success message as JSON
        return response()->json(['message' => 'Document deleted successfully']);
    }
}
