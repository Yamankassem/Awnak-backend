<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call([]);
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        $guard = config('auth.defaults.guard', 'web');

        // Roles 
        $roles = [
            'super-admin',
            'volunteer',
            'opportunity-manager',
            'organization-partner',
            'volunteer-coordinator',
            'auditor',
        ];

        foreach ($roles as $r) {
            Role::findOrCreate($r, $guard);
        }

        // Permissions (مثال عملي قابل للتوسع)
        $permissions = [
            'core.users.view',
            'core.users.manage',
            'core.roles.manage',
            'core.locations.manage',
            'core.translations.manage',
            'reports.view',
        ];

        foreach ($permissions as $p) {
            Permission::findOrCreate($p, $guard);
        }

        // super-admin كل الصلاحيات
        $super = Role::findByName('super-admin', $guard);
        $super->syncPermissions(Permission::where('guard_name', $guard)->pluck('name')->toArray());

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
