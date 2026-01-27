<?php

namespace Modules\Organizations\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Organizations\Models\Document;

class DocumentAccessMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        $documentId = $request->route('document');

        $document = Document::with('opportunity.organization')->find($documentId);

        if (! $document) {
            return response()->json(['message' => 'Document not found'], 404);
        }

        $organization = $document->opportunity->organization;

        // شرط: أدمن أو صاحب المنظمة
        if ($user->role === 'SuperAdmin' || $organization->user_id === $user->id) {
            return $next($request);
        }

        return response()->json(['message' => 'Access denied: not authorized for this document'], 403);
    }
}
