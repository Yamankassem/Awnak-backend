<?php

namespace Modules\Core\Services;

use Spatie\Permission\Models\Role;
use Spatie\Activitylog\Models\Activity;


class RoleService
{
    public function handle() {}

    public function list()
    {
        return Role::query()
            ->with('permissions')
            ->where('guard_name', 'sanctum')
            ->paginate(10);
    }

    public function create(array $data, int $actorId): Role
    {
        $role = Role::create([
            'name' => $data['name'],
            'guard_name' => 'sanctum',
        ]);

        Activity::create([
            'log_name'     => 'audit',
            'description'  => 'roles.created',
            'subject_type' => 'role',
            'subject_id'   => $role->id,
            'causer_type'  => 'core.user',
            'causer_id'    => $actorId,
            'properties'   => [
                'name' => $role->name,
                'permissions' => $data['permissions'] ?? [],
            ],
        ]);

        if (!empty($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }

        return $role->load('permissions');
    }

    public function update(Role $role, array $data, int $actorId): Role
    {
        if (isset($data['name'])) {
            $role->update(['name' => $data['name']]);
        }

        if (array_key_exists('permissions', $data)) {
            $role->syncPermissions($data['permissions']);
        }

        Activity::create([
            'log_name'     => 'audit',
            'description'  => 'roles.updated',
            'subject_type' => 'role',
            'subject_id'   => $role->id,
            'causer_type'  => 'core.user',
            'causer_id'    => $actorId,
            'properties'   => array_keys($data),
        ]);

        return $role->load('permissions');
    }

    public function delete(Role $role, int $actorId): void
    {
        // do not delete system-admin
        if ($role->name === 'system-admin') {
            abort(403, 'Cannot delete system-admin role.');
        }
        Activity::create([
            'log_name'     => 'audit',
            'description'  => 'roles.deleted',
            'subject_type' => 'role',
            'subject_id'   => $role->id,
            'causer_type'  => 'core.user',
            'causer_id'    => $actorId,
            'properties'   => [
                'name' => $role->name,
            ],
        ]);

        $role->delete();
    }
}
