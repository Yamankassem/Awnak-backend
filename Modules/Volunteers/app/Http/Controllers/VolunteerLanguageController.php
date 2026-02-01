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
    public function __construct(
        private VolunteerLanguageService $service
    ) {}

    public function index(Request $request)
    {
        $profile = $request->user()->volunteerProfile;

        return static::success(
            data: $this->service->list($profile)
        );
    }

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

    public function update(UpdateVolunteerLanguageRequest $request, VolunteerLanguage $volunteerLanguage)
    {
        $this->authorize('update', $volunteerLanguage);

        $language = $this->service->update($volunteerLanguage, $request->validated(),$request->user());

        return static::success(
            data: $language,
            message: 'language.updated'
        );
    }

    public function destroy(VolunteerLanguage $volunteerLanguage,Request $request)
    {
        $this->authorize('delete', $volunteerLanguage);

        $this->service->delete($volunteerLanguage,$request->user());

        return static::success(message: 'language.deleted');
    }
}
