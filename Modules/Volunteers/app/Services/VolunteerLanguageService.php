<?php

namespace Modules\Volunteers\Services;

use Modules\Core\Models\User;
use Modules\Volunteers\Models\VolunteerProfile;
use Modules\Volunteers\Models\VolunteerLanguage;
use Modules\Volunteers\Traits\LogsVolunteerActivity;

class VolunteerLanguageService
{
    use LogsVolunteerActivity;
    /**
     * List all languages associated with a volunteer profile.
     *
     * @param VolunteerProfile $profile
     * @return \Illuminate\Support\Collection
     */
    public function list(VolunteerProfile $profile)
    {
        return $profile->languages()->withPivot('level')->get();
    }

    /**
     * Attach a new language to a volunteer profile.
     *
     * @param VolunteerProfile $profile
     * @param array $data Validated input data
     * @param User $actor The authenticated user performing the action
     * @return VolunteerLanguage
     */
    public function create(VolunteerProfile $profile, array $data, User $actor)
    {
        $exists = $profile->languages()
            ->where('language_id', $data['language_id'])
            ->exists();

        if ($exists) {
            abort(422, 'Language already added.');
        }

        $language = VolunteerLanguage::create([
            'volunteer_profile_id' => $profile->id,
            'language_id' => $data['language_id'],
            'level' => $data['level'],
        ]);

        //activity log
        $this->log(
            'volunteer.language.added',
            $language,
            $actor,
            ['language_id' => $data['language_id']]
        );

        return $language;
    }

    /**
     * Update volunteer language level.
     *
     * @param VolunteerLanguage $language
     * @param array $data
     * @param User $actor
     * @return VolunteerLanguage
     */
    public function update(VolunteerLanguage $language, array $data, User $actor)
    {
        $language->update($data);

        //activity log
        $this->log(
            'volunteer.language.updated',
            $language,
            $actor,
        );

        return $language->refresh();
    }

    /**
     * Remove a language from a volunteer profile.
     *
     * @param VolunteerLanguage $language
     * @param User $actor
     * @return void
     */
    public function delete(VolunteerLanguage $language, User $actor): void
    {
        $language->delete();

        //activity log
        $this->log(
            'volunteer.language.deleted',
            $language,
            $actor,
        );
    }
}
