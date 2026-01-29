<?php

namespace Modules\Organizations\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Organizations\Models\Document;

/**
 * Middleware: DocumentAccessMiddleware
 *
 * Controls access to document-related requests.
 *
 * Rules:
 * - GET (index/show): allowed for all authenticated users.
 * - POST (create): restricted to roles ['system-admin', 'opportunity-manager', 'organization-admin'].
 * - PUT/DELETE (update/delete): restricted to the same roles or the organization owner.
 * - Other users: read-only access.
 *
 * @param Request $request The incoming HTTP request
 * @param Closure $next The next middleware/controller in the pipeline
 * @return \Illuminate\Http\JsonResponse|mixed
 *
 * @apiResponse 200 {
 *   "message": "Authorized request"
 * }
 *
 * @apiResponse 403 {
 *   "message": "Access denied: not authorized to create documents"
 * }
 *
 * @apiResponse 403 {
 *   "message": "Access denied: not authorized for this document"
 * }
 *
 * @apiResponse 404 {
 *   "message": "Document not found"
 * }
 */


class DocumentAccessMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($request->isMethod('GET')) {
            return $next($request);
        }


        if ($request->isMethod('POST')) {
            if ($user->hasRole(['system-admin', 'opportunity-manager', 'organization-admin'])) {
                return $next($request);
            }
            return response()->json(['message' => 'Access denied: not authorized to create documents'], 403);
        }


        $documentId = $request->route('document') ?? $request->route('id');

        $document = Document::with('opportunity.organization')->find($documentId);

        if (! $document) {
            return response()->json(['message' => 'Document not found'], 404);
        }

        $organization = $document->opportunity->organization;


        if ($user->hasRole(['system-admin', 'opportunity-manager', 'organization-admin'])) {
            return $next($request);
        }


        if ($organization->user_id === $user->id) {
            return $next($request);
        }


        if ($request->isMethod('GET')) {
            return $next($request);
        }

        return response()->json(['message' => 'Access denied: not authorized for this document'], 403);
    }
}
