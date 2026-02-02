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
            'organization.status.update',


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
            'organization.opportunities.update',
            'organization.opportunities.delete',
            'organization.opportunities.publish',
            'evaluations.create',
            'evaluations.update',
            'evaluations.delete',
            'evaluations.view',
            'evaluations.viewAny',

            'volunteerBadges.create',
            'volunteerBadges.update',
            'volunteerBadges.delete',
            'volunteerBadges.view',
            'volunteerBadges.viewAny',

            // Volunteer Coordinator
            'volunteers.read.assigned',
            'tasks.read.managed',
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
                'evaluations.create',
                'evaluations.update',
                'evaluations.delete',
                'evaluations.view',
                'evaluations.viewAny',
                'organization.status.update',

                'skills.read',
                'skills.create',
                'skills.update',
                'skills.delete',
                'interests.read',
                'interests.create',
                'interests.update',
                'interests.delete',

                'volunteerBadges.create',
                'volunteerBadges.update',
                'volunteerBadges.delete',
                'volunteerBadges.view',
                'volunteerBadges.viewAny',

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
                'certificate.view',
                'certificate.viewAny',
                'volunteerBadges.view',
                'volunteerBadges.viewAny',
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
                'organization.opportunities.update',
                'organization.opportunities.delete',
                'organization.opportunities.publish',
                
                'skills.read',
                'interests.read',
            ],

            'volunteer-coordinator' => [
                'volunteers.read.assigned',
                'tasks.read.managed',
                'notifications.send',
                'evaluations.create',
                'evaluations.update',
                'evaluations.delete',
                'evaluations.view',
                'evaluations.viewAny',
                'skills.read',
                'interests.read',
                 'volunteerBadges.create',
                'volunteerBadges.update',
                'volunteerBadges.delete',
                'volunteerBadges.view',
                'volunteerBadges.viewAny',
            ],

            'performance-auditor' => [
                'reports.performance.read',
                'certificates.create',
                'certificates.update',
                'certificates.delete',
                'certificate.view',
                'certificate.viewAny',
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
