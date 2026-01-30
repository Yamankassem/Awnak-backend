<?php

namespace Modules\Organizations\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use Modules\Organizations\Http\Requests\DocumentRequest;
use Modules\Organizations\Models\Document;
use Modules\Organizations\Models\Opportunity;
use Symfony\Component\HttpFoundation\JsonResponse;

class DocumentController extends Controller
{
    use AuthorizesRequests;

    /**
     * Index: Retrieve all documents with their associated media.
     */
    public function index(): JsonResponse
    {
        $documents = Document::with('media')->get();

        return response()->json([
            'data' => $documents
        ]);
    }

    /**
     * Store: Upload a new document with file.
     */
    public function store(DocumentRequest $request): JsonResponse
    {
        $data = $request->validated();

        $opportunity = Opportunity::findOrFail($data['opportunity_id']);

        $document = Document::create([
            'opportunity_id' => $opportunity->id,
            'title'          => $data['title'],
            'description'    => $data['description'] ?? null,
        ]);

        // رفع الملف عبر Media Library
        if ($request->hasFile('file')) {
            $document->addMedia($request->file('file'))
                ->toMediaCollection('documents');
        }

        return response()->json([
            'message' => 'Document uploaded successfully',
            'data'    => $document->load('media')
        ], 201);
    }

    /**
     * Show: Display a single document file by ID.
     */
    public function show(Document $document)
    {
        $media = $document->getFirstMedia('documents');

        if (! $media) {
            return response()->json(['message' => 'No file attached'], 404);
        }

        return response()->file($media->getPath());
    }

    /**
     * Update: Modify an existing document and replace its file if provided.
     */
    public function update(DocumentRequest $request, Document $document): JsonResponse
    {
        $data = $request->validated();
        $document->update(['title' => $data['title'], 'description' => $data['description'] ?? $document->description,]);
        if ($request->hasFile('file')) {
            $document->clearMediaCollection('documents');
            $document->addMedia($request->file('file'))->toMediaCollection('documents');
        }
        return response()->json(['message' => 'Document updated successfully', 'data' => $document->load('media')], 200);
    }

    
    public function destroy(Document $document): JsonResponse
    {
        $document->clearMediaCollection('documents');
        $document->delete();
        return response()->json(['message' => 'Document deleted successfully'], 200);
    }
}
