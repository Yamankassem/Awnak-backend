<?php

namespace Modules\Volunteers\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Volunteers\Models\Language;
use Modules\Volunteers\Services\LanguageService;
use Modules\Volunteers\Http\Requests\Languages\StoreLanguageRequest;
use Modules\Volunteers\Http\Requests\Languages\UpdateLanguageRequest;

class LanguageController extends Controller
{
    /**
     * @param LanguageService $service Language business logic service
     */
    public function __construct(
        private LanguageService $service
    ) {}

    /**
     * List all available languages.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return static::success(
            data: $this->service->list()
        );
    }

    /**
     * Store a new language.
     *
     * @param StoreLanguageRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreLanguageRequest $request)
    {
        $language = $this->service->create($request->validated());

        return static::success(
            data: $language,
            message: 'language.created',
            status: 201
        );
    }

    /**
     * Update an existing language.
     *
     * @param UpdateLanguageRequest $request
     * @param Language $language
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateLanguageRequest $request, Language $language)
    {
        $language = $this->service->update(
            $language,
            $request->validated()
        );

        return static::success(
            data: $language,
            message: 'language.updated'
        );
    }

    /**
     * Delete a language.
     *
     * @param Language $language
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Language $language)
    {
        $this->service->delete($language);

        return static::success(
            message: 'language.deleted'
        );
    }
}
