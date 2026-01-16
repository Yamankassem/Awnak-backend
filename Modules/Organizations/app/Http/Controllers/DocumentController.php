<?php

namespace Modules\Organizations\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Organizations\Models\Document;
use Modules\Organizations\Http\Resources\DocumentResource;

class DocumentController extends Controller
{
    /**
     * Display a listing of documents for a specific opportunity.
     *
     * @param int $opportunityId
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index($opportunityId)
    {
        $documents = Document::where('opportunity_id', $opportunityId)->get();
        return DocumentResource::collection($documents);
    }

    /**
     * Store a newly created document for an opportunity.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $opportunityId
     * @return DocumentResource
     */
    public function store(Request $request, $opportunityId)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'file'  => 'required|file|max:2048', // Limit file size to 2MB
        ]);

        // Save file to storage
        $path = $request->file('file')->store('documents', 'public');

        $document = Document::create([
            'opportunity_id' => $opportunityId,
            'title'          => $validated['title'],
            'file_path'      => $path,
            'file_type'      => $request->file('file')->getClientOriginalExtension(),
            'file_size'      => $request->file('file')->getSize(),
        ]);

        return new DocumentResource($document);
    }

    /**
     * Remove the specified document from storage.
     *
     * @param Document $document
     * @return \Illuminate\Http\Response
     */
    public function destroy(Document $document)
    {
        $document->delete();
        return response()->noContent();
    }
}
