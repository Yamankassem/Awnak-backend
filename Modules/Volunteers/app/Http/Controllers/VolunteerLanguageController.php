<?php

namespace Modules\Volunteers\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Volunteers\Models\VolunteerLanguage;
use Modules\Volunteers\Services\VolunteerLanguageService;
use Modules\Volunteers\Http\Requests\VolunteerLanguages\StoreVolunteerLanguageRequest;
use Modules\Volunteers\Http\Requests\VolunteerLanguages\UpdateVolunteerLanguageRequest;

class VolunteerLanguageController extends Controller
{
    public function __construct(private VolunteerLanguageService $service) {}

    /**
     * List all languages associated with the authenticated volunteer.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $profile = $request->user()->volunteerProfile;

        return static::success(
            data: $this->service->list($profile)
        );
    }
    /**
     * Attach a new language to the volunteer profile.
     *
     * @param StoreVolunteerLanguageRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreVolunteerLanguageRequest $request)
    {
        $this->authorize('create', VolunteerLanguage::class);

        $profile = $request->user()->volunteerProfile;

        $language = $this->service->create(
            $profile,
            $request->validated(),
            $request->user()
        );

        return static::success(
            data: $language,
            message: 'language.added',
            status: 201
        );
    }
    /**
     * Update the proficiency level of a volunteer language.
     *
     * @param UpdateVolunteerLanguageRequest $request
     * @param VolunteerLanguage $volunteerLanguage
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateVolunteerLanguageRequest $request, VolunteerLanguage $volunteerLanguage)
    {
        $this->authorize('update', $volunteerLanguage);

        $language = $this->service->update(
            $volunteerLanguage,
             $request->validated(),
             $request->user()
             );

        return static::success(
            data: $language,
            message: 'language.updated'
        );
    }
    /**
     * Remove a language from the volunteer profile.
     *
     * @param VolunteerLanguage $volunteerLanguage
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(VolunteerLanguage $volunteerLanguage,Request $request)
    {
        $this->authorize('delete', $volunteerLanguage);

        $this->service->delete($volunteerLanguage,$request->user());

        return static::success(message: 'language.deleted');
    }
}
