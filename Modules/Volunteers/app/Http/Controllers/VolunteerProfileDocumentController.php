<?php

namespace Modules\Volunteers\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Core\Models\Media;
use App\Http\Controllers\Controller;
use Modules\Volunteers\Transformers\VolunteerDocumentResource;
use Modules\Volunteers\Services\VolunteerProfileDocumentService;
use Modules\Volunteers\Http\Requests\StoreVolunteerDocumentRequest;

class VolunteerProfileDocumentController extends Controller
{
    public function __construct(
        private VolunteerProfileDocumentService $service
    ) {}

    public function index(Request $request)
    {
        $profile = $request->user()->volunteerProfile;

        return static::success(
            data: VolunteerDocumentResource::collection(
                $this->service->list($profile)
            )
        );
    }

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

    public function destroy(Media $media)
    {
        $this->authorize('delete', $media);

        $this->service->delete($media);

        return static::success(message: 'document.deleted');
    }

}
