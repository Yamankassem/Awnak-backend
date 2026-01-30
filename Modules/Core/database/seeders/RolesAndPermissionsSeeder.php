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
            'skills.read',
            'skills.create',
            'skills.update',
            'skills.delete',
            'interests.read',
            'interests.create',
            'interests.update',
            'interests.delete',

            // Volunteer
            'profile.read.own',
            'profile.update.own',
            'opportunities.search',
            'opportunities.apply',
            'tasks.read.assigned',
            'tasks.update.assigned',
            'evaluations.read.own',
            'notes.create.assigned_tasks',

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
                'skills.read',
                'skills.create',
                'skills.update',
                'skills.delete',
                'interests.read',
                'interests.create',
                'interests.update',
                'interests.delete',
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
                'skills.read',
                'interests.read',
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
                'skills.read',
                'interests.read',
            ],

            'organization-admin' => [
                'organization.volunteers.read',
                'organization.volunteers.evaluate',
                'organization.opportunities.create',
                'organization.opportunities.publish',
                'skills.read',
                'interests.read',
            ],

            'volunteer-coordinator' => [
                'volunteers.read.assigned',
                'tasks.read.managed',
                'evaluations.create',
                'notifications.send',
                'skills.read',
                'interests.read',
            ],

            'performance-auditor' => [
                'reports.performance.read',
                'skills.read',
                'interests.read',
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
