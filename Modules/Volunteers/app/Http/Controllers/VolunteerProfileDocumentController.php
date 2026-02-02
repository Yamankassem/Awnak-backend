<?php

namespace Modules\Volunteers\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Core\Models\Media;
use App\Http\Controllers\Controller;
use Modules\Volunteers\Transformers\VolunteerDocumentResource;
use Modules\Volunteers\Services\VolunteerProfileDocumentService;
use Modules\Volunteers\Http\Requests\StoreVolunteerDocumentRequest;

/**
 * Class VolunteerProfileDocumentController
 *
 * Handles volunteer document uploads and management.
 *
 * @package Modules\Volunteers\Http\Controllers
 */
class VolunteerProfileDocumentController extends Controller
{
    public function __construct(private VolunteerProfileDocumentService $service) {}
    /**
     * List documents uploaded by the volunteer.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $profile = $request->user()->volunteerProfile;

        return static::success(
            data: VolunteerDocumentResource::collection(
                $this->service->list($profile)
            )
        );
    }
     /**
     * Upload a new volunteer document.
     *
     * @param StoreVolunteerDocumentRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreVolunteerDocumentRequest $request)
    {
        $profile = $request->user()->volunteerProfile;

        $media = $this->service->upload(
            $profile,
            $request->file('file')
        );

        return static::success(
            data: new VolunteerDocumentResource($media),
            message: 'document.uploaded',
            status: 201
        );
    }
    /**
     * Delete a volunteer document.
     *
     * @param Media $media
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Media $media)
    {
        $this->authorize('delete', $media);

        $this->service->delete($media);

        return static::success(message: 'document.deleted');
    }

}
