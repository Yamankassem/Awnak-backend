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
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $guard = 'sanctum';

        $permissions = [
            // Users & Roles (System Admin)
            'users.create',
            'users.read',
            'users.update',
            'users.delete',
            'roles.create',
            'roles.read',
            'roles.update',
            'roles.delete',
            'badges.create',
            'badges.update',
            'badges.delete',
            'certificates.create',
            'certificates.update',
            'certificates.delete',
            'certificate.view',
            'certificate.viewAny',

            // Volunteer
            'profile.read.own',
            'profile.update.own',
            'opportunities.search',
            'opportunities.apply',
            'tasks.read.assigned',
            'tasks.update.assigned',
            'evaluations.read.own',
            'notes.create.assigned_tasks',
            'certificate.view',
            'certificate.viewAny',

            // Opportunity Manager
            'opportunities.create',
            'opportunities.read.own',
            'opportunities.update.own',
            'opportunities.delete.own',
            'applications.read',
            'applications.review',
            'volunteers.read.applicants',
            'volunteers.assign',

            // Organization
            'organization.volunteers.read',
            'organization.volunteers.evaluate',
            'organization.opportunities.create',
            'organization.opportunities.publish',

            // Volunteer Coordinator
            'volunteers.read.assigned',
            'tasks.read.managed',
            'evaluations.create',
            'notifications.send',

            // Auditor
            'reports.performance.read',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate([
                'name' => $name,
                'guard_name' => $guard,
            ]);
        }

        $roles = [
            'system-admin' => [
                'users.create',
                'users.read',
                'users.update',
                'users.delete',
                'roles.create',
                'roles.read',
                'roles.update',
                'roles.delete',
                'badges.create',
                'badges.update',
                'badges.delete',
                'certificates.create',
                'certificates.update',
                'certificates.delete',
                'certificate.view',
                'certificate.viewAny',

            ],

            'volunteer' => [
                'profile.read.own',
                'profile.update.own',
                'opportunities.search',
                'opportunities.apply',
                'tasks.read.assigned',
                'tasks.update.assigned',
                'evaluations.read.own',
                'notes.create.assigned_tasks',
            ],

            'opportunity-manager' => [
                'opportunities.create',
                'opportunities.read.own',
                'opportunities.update.own',
                'opportunities.delete.own',
                'applications.read',
                'applications.review',
                'volunteers.read.applicants',
                'volunteers.assign',
            ],

            'organization-admin' => [
                'organization.volunteers.read',
                'organization.volunteers.evaluate',
                'organization.opportunities.create',
                'organization.opportunities.publish',
            ],

            'volunteer-coordinator' => [
                'volunteers.read.assigned',
                'tasks.read.managed',
                'evaluations.create',
                'notifications.send',
            ],

            'performance-auditor' => [
                'reports.performance.read',
                'certificates.create',
                'certificates.update',
                'certificates.delete',
                'certificate.view',
                'certificate.viewAny',
            ],
        ];

        foreach ($roles as $roleName => $perms) {
            $role = Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => $guard,
            ]);

            $role->syncPermissions($perms);
        }
    }
}
