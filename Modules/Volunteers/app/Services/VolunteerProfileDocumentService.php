<?php

namespace Modules\Volunteers\Services;

use Modules\Core\Models\Media;
use Modules\Volunteers\Models\VolunteerProfile;

class VolunteerProfileDocumentService
{
    public function handle() {}

    public function list(VolunteerProfile $profile)
    {
        return $profile->getMedia('documents');
    }

    public function upload(VolunteerProfile $profile, $file): Media
    {
        return $profile
            ->addMedia($file)
            ->toMediaCollection('documents');
    }

    public function delete(Media $media): void
    {
        $media->delete();
    }
}
