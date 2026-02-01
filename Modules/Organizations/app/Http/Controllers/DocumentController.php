<?php

namespace Modules\Organizations\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use Modules\Organizations\Http\Requests\DocumentRequest;
use Modules\Organizations\Models\Document;
use Modules\Organizations\Models\Opportunity;
use Modules\Organizations\Services\DocumentService;
use Symfony\Component\HttpFoundation\JsonResponse;

class DocumentController extends Controller
{
    use AuthorizesRequests;
    /**
     * DocumentController constructor.
     *
     * Injects the DocumentService dependency into the controller.
     * This allows the controller to delegate business logic related
     * to documents (create, update, delete, etc.) to the service layer.
     *
     * @param DocumentService $documentService
     *        The service responsible for handling document operations
     *        such as creation, update, deletion, and media management.
     *
     * @return void
     */
    protected DocumentService $documentService;

    public function __construct(DocumentService $documentService)
    {
        $this->documentService = $documentService;
    }

    /**
     * Index: Retrieve all documents for a specific opportunity.
     */
    public function index(Opportunity $opportunity): JsonResponse
    {
        $this->authorize('viewAny', Document::class);
        $documents = $opportunity->documents()->with('media')->get();
        return response()->json($documents);
    }

    /**
     * Store a new document.
     *
     * @param DocumentRequest $request Validated request containing document data
     * @return JsonResponse
     *
     * @apiResponse 201 {
     *   "status": "success",
     *   "message": "Document uploaded successfully.",
     *   "data": {
     *     "id": 1,
     *     "title": "Contract",
     *     "description": "Signed contract",
     *     "media": [...]
     *   }
     * }
     *
     * @apiResponse 500 {
     *   "status": "error",
     *   "message": "Failed to upload document",
     *   "error": "Exception message"
     * }
     */
    public function store(DocumentRequest $request): JsonResponse
    {

        $this->authorize('create', Document::class);

        try {
            $data = $request->validated();

            $opportunity = Opportunity::findOrFail($data['opportunity_id']);
            $data['opportunity_id'] = $opportunity->id;

            $document = $this->documentService->create($data);

            return response()->json([
                'status' => 'success',
                'message' => __('documents.uploaded'),
                'data' => $document->load('media')
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => __('documents.not_found'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display a single document by ID.
     *
     * @param int $id The ID of the document to retrieve
     * @return JsonResponse
     *
     * @apiResponse 200 {
     *   "status": "success",
     *   "message": "Document retrieved successfully.",
     *   "data": {
     *     "document": {
     *       "id": 1,
     *       "title": "Contract",
     *       "description": "Signed contract",
     *       "created_at": "2026-01-29T00:00:00Z",
     *       "updated_at": "2026-01-29T00:00:00Z"
     *     },
     *     "opportunity": {
     *       "id": 5,
     *       "name": "Volunteer Program"
     *     },
     *     "media": [
     *       {
     *         "id": 10,
     *         "file_name": "contract.pdf",
     *         "url": "https://example.com/storage/documents/contract.pdf"
     *       }
     *     ]
     *   }
     * }
     *
     * @apiResponse 404 {
     *   "status": "error",
     *   "message": "Document not found."
     * }
     */
    public function show($id): JsonResponse
    {
        try {
            $document = Document::with(['opportunity', 'media'])->findOrFail($id);
            $this->authorize('view', $document);
            return response()->json([
                'status' => 'success',
                'message' => __('documents.retrieved'),
                // من ملف الترجمة
                'data' => [
                    'document' => [
                        'id' => $document->id,
                        'title' => $document->title,
                        'description' => $document->description,
                        'created_at' => $document->created_at,
                        'updated_at' => $document->updated_at,
                    ],
                    'opportunity' => $document->opportunity,
                    'media' => $document->media->map(fn($m) => [
                        'id' => $m->id,
                        'file_name' => $m->file_name,
                        'url' => $m->getUrl(),
                    ]),
                ]
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => __('documents.not_found'),
            ], 404);
        }
    }




    /**
     * Update an existing document.
     *
     * @param DocumentRequest $request Validated request containing document data
     * @param int $id The ID of the document to update
     * @return JsonResponse
     *
     * @apiResponse 200 {
     *   "status": "success",
     *   "message": "Document updated successfully.",
     *   "data": {
     *     "id": 1,
     *     "title": "Updated Contract",
     *     "description": "New description",
     *     "media": [...]
     *   }
     * }
     *
     * @apiResponse 404 {
     *   "status": "error",
     *   "message": "Document not found."
     * }
     *
     * @apiResponse 500 {
     *   "status": "error",
     *   "message": "Failed to update document",
     *   "error": "Exception message"
     * }
     */
    public function update(DocumentRequest $request, $id): JsonResponse
    {
        try {
            $document = Document::findOrFail($id);
            $this->authorize('update', $document);
            $data = $request->validated();
            $document = $this->documentService->update($document, $data);

            return response()->json([
                'status' => 'success',
                'message' => __('documents.updated'),
                'data' => $document->load('media')
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => __('documents.not_found'),
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => __('documents.update_failed'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    /**
     * Delete a document and its associated media.
     *
     * @param int $id The ID of the document to delete
     * @return JsonResponse
     *
     * @apiResponse 200 {
     *   "status": "success",
     *   "message": "Document deleted successfully."
     * }
     *
     * @apiResponse 404 {
     *   "status": "error",
     *   "message": "Document not found."
     * }
     *
     * @apiResponse 500 {
     *   "status": "error",
     *   "message": "Failed to delete document",
     *   "error": "Exception message"
     * }
     */
    public function destroy($id): JsonResponse
    {
        try {
            $document = Document::findOrFail($id);
            $this->authorize('delete', $document);
            $this->documentService->delete($document);
            return response()->json([
                'status' => 'success',
                'message' => __('documents.deleted')
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['status' => 'error', 'message' => __('documents.not_found'),], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => __('documents.delete_failed'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
