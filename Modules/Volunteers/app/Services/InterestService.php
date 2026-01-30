<?php

namespace Modules\Volunteers\Services;

use Illuminate\Support\Str;
use Modules\Volunteers\Models\Interest;
use Illuminate\Pagination\LengthAwarePaginator;

class InterestService
{
    public function handle() {}

    public function paginate(): LengthAwarePaginator
    {
        return Interest::query()->paginate(10);
    }

    public function create(array $data): Interest
    {
        return Interest::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
        ]);
    }

    public function update(Interest $interest, array $data): Interest
    {
        if (isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $interest->update($data);

        return $interest->refresh();
    }

    public function delete(Interest $interest): void
    {
        $interest->delete();
    }
}
