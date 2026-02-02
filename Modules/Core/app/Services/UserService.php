<?php

namespace Modules\Core\Services;

use Modules\Core\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Activitylog\Models\Activity;

class UserService
{
    public function handle() {}

    public function paginate(int $perPage = 15)
    {
        return User::query()
            ->with('roles')
            ->paginate($perPage);
    }

    public function create(array $data, int $actorId): User
    {
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'status'   => $data['status'] ?? 'active',
        ]);

        Activity::create([
            'log_name'     => 'audit',
            'description'  => 'users.created',
            'subject_type' => 'user',
            'subject_id'   => $user->id,
            'causer_type'  => 'core.user',
            'causer_id'    => $actorId,
            'properties'   => ['email' => $user->email],
        ]);

        return $user;
    }

    public function update(User $user, array $data, int $actorId): User
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        Activity::create([
            'log_name'     => 'audit',
            'description'  => 'users.updated',
            'subject_type' => 'user',
            'subject_id'   => $user->id,
            'causer_type'  => 'core.user',
            'causer_id'    => $actorId,
            'properties'   => array_keys($data),
        ]);

        return $user;
    }

    public function delete(User $user, int $actorId): void
    {
        if ($user->id === $actorId) {
            abort(403, 'You cannot delete yourself.');
        }

        Activity::create([
            'log_name'     => 'audit',
            'description'  => 'users.deleted',
            'subject_type' => 'user',
            'subject_id'   => $user->id,
            'causer_type'  => 'core.user',
            'causer_id'    => $actorId,
            'properties'   => ['email' => $user->email],
        ]);

        $user->delete();
    }

    public function syncRoles(User $user, array $roles, int $actorId): User
    {
        // do not change role for system-admin 
        if ($user->id === auth()->id() && in_array('system-admin', $roles)) {
            // return error
        }

        $user->syncRoles($roles);

        Activity::create([
            'log_name'     => 'audit',
            'description'  => 'users.roles.updated',
            'subject_type' => 'user',
            'subject_id'   => $user->id,
            'causer_type'  => 'core.user',
            'causer_id'    => $actorId,
            'properties'   => ['roles' => $roles],
        ]);

        return $user->load('roles');
    }
}
