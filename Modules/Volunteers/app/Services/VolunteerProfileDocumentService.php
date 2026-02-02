<?php

namespace Modules\Volunteers\Services;

use Modules\Core\Models\Media;
use Modules\Volunteers\Models\VolunteerProfile;
/**
 * Class VolunteerProfileDocumentService
 *
 * Handles uploading, listing, and deleting documents
 * associated with volunteer profiles using Media Library.
 *
 * @package Modules\Volunteers\Services
 */
class VolunteerProfileDocumentService
{
    /**
     * List all documents attached to a volunteer profile.
     *
     * @param VolunteerProfile $profile
     * @return \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection
     */
    public function list(VolunteerProfile $profile)
    {
        return $profile->getMedia('documents');
    }
    /**
     * Upload a document to the volunteer profile.
     *
     * @param VolunteerProfile $profile
     * @param mixed $file
     * @return Media
     */
    public function upload(VolunteerProfile $profile, $file): Media
    {
        return $profile
            ->addMedia($file)
            ->toMediaCollection('documents');
    }
    /**
     * Delete a document from the system.
     *
     * @param Media $media
     * @return void
     */
    public function delete(Media $media): void
    {
        $media->delete();
    }
}
