<?php

namespace Modules\Volunteers\Services;

use Modules\Core\Models\User;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Validation\ValidationException;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Volunteers\Models\VolunteerProfile;


class VolunteerVerificationService
{
    public function handle() {}

    public function verify(VolunteerProfile $volunteerProfile, User $actor): VolunteerProfile
    {
        if ($volunteerProfile->is_verified) {
            throw ValidationException::withMessages([
                'profile' => ['Volunteer is already verified.'],
            ]);
        }

        if ($volunteerProfile->status !== 'active') {
            abort(422, 'Profile cannot be verified in current status.');
        }

        $volunteerProfile->update([
            'is_verified' => true,
            'verified_at' => now(),
            'status' => 'active',
        ]);

        // Activity Log
        Activity::create([
            'log_name'     => 'audit',
            'description'  => 'volunteer.verified',
            'subject_type' => VolunteerProfile::class,
            'subject_id'   => $volunteerProfile->id,
            'causer_type'  => User::class,
            'causer_id'    => $actor->id,
            'properties'   => [
                'volunteer_user_id' => $volunteerProfile->user_id,
            ],
        ]);

        return $volunteerProfile->refresh();
    }

    public function reject(VolunteerProfile $profile, User $actor): VolunteerProfile
    {
        // Prevent rejection if it is not already verified (optional but logical)
        if (!$profile->is_verified) {
            throw ValidationException::withMessages([
                'profile' => ['Volunteer is not verified yet.'],
            ]);
        }
        $profile->update([
            'is_verified' => false,
            'verified_at' => null,
        ]);

        // Activity Log
        Activity::create([
            'log_name'     => 'audit',
            'description'  => 'volunteer.rejected',
            'subject_type' => VolunteerProfile::class,
            'subject_id'   => $profile->id,
            'causer_type'  => User::class,
            'causer_id'    => $actor->id,
        ]);

        return $profile->refresh();
    }

    public function pending(): LengthAwarePaginator
    {
        return VolunteerProfile::query()
            ->where('is_verified', false)
            ->with([
                'user',
                'skills',
                'interests',
                'location',
            ])
            ->latest()
            ->paginate(10);
    }
}
