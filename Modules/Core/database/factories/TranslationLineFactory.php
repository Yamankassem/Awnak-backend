<?php

namespace Modules\Core\Database\Factories;

use Spatie\TranslationLoader\LanguageLine;

/**
 * Class TranslationLineFactory
 *
 * Helper factory for creating or updating translation
 * lines using Spatie Translation Loader.
 *
 * This is NOT an Eloquent factory.
 */
class TranslationLineFactory
{
    /**
     * Create or update a translation line.
     *
     * @param string $group Translation group (e.g. "core")
     * @param string $key   Translation key (e.g. "roles.super_admin")
     * @param array  $text  Translated values keyed by locale.
     *
     * @return LanguageLine
     */
    public static function create(
        string $group,
        string $key,
        array $text
    ): LanguageLine {
        $model = config('translation-loader.model')
            ?? LanguageLine::class;

        return $model::updateOrCreate(
            [
                'group' => $group,
                'key'   => $key,
            ],
            [
                'text' => $text,
            ]
        );
    }
}
