<?php

namespace Modules\Volunteers\Services;

use Illuminate\Support\Str;
use Modules\Volunteers\Models\Interest;
use Illuminate\Pagination\LengthAwarePaginator;

class InterestService
{
    /**
     * Paginate interests list.
     *
     * @return LengthAwarePaginator Paginated list of interests
     */
    public function paginate(): LengthAwarePaginator
    {
        return Interest::query()->paginate(10);
    }

    /**
     * Create a new interest.
     *
     * @param array $data Validated interest data
     * @return Interest Newly created interest instance
     */
    public function create(array $data): Interest
    {
        return Interest::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
        ]);
    }

    /**
     * Update an existing interest.
     *
     * Automatically regenerates slug if name is updated.
     *
     * @param Interest $interest Target interest
     * @param array $data Validated update data
     * @return Interest Updated interest instance
     */
    public function update(Interest $interest, array $data): Interest
    {
        if (isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $interest->update($data);

        return $interest->refresh();
    }

    /**
     * Delete an interest.
     *
     * @param Interest $interest Target interest
     * @return void
     */
    public function delete(Interest $interest): void
    {
        $interest->delete();
    }
}
