<?php

namespace Modules\Volunteers\Services;

use Modules\Volunteers\Models\Language;
use Modules\Volunteers\Traits\LogsVolunteerActivity;

class LanguageService
{
    use LogsVolunteerActivity;

    /**
     * Retrieve all languages ordered alphabetically.
     *
     * @return \Illuminate\Support\Collection<int, Language>
     */
    public function list()
    {
        return Language::orderBy('name')->get();
    }

    /**
     * Create a new language.
     *
     * @param array $data Validated language data
     * @return Language Newly created language instance
     */
    public function create(array $data): Language
    {
        return Language::create($data);
    }

     /**
     * Update an existing language.
     *
     * @param Language $language Target language
     * @param array $data Validated update data
     * @return Language Updated language instance
     */
    public function update(Language $language, array $data): Language
    {
        $language->update($data);
        return $language->refresh();
    }

    /**
     * Delete a language.
     *
     * @param Language $language Target language
     * @return void
     */
    public function delete(Language $language): void
    {
        $language->delete();
    }
}
