<?php

namespace Modules\Volunteers\Services;

use Modules\Volunteers\Models\Language;
use Modules\Volunteers\Traits\LogsVolunteerActivity;

class LanguageService
{
    use LogsVolunteerActivity;
    public function handle() {}
    public function list()
    {
        return Language::orderBy('name')->get();
    }

    public function create(array $data): Language
    {
        return Language::create($data);
    }

    public function update(Language $language, array $data): Language
    {
        $language->update($data);
        return $language->refresh();
    }

    public function delete(Language $language): void
    {
        $language->delete();
    }
}
