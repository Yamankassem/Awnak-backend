<?php

namespace Modules\Volunteers\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureVolunteerIsActive
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // لا يوجد مستخدم (احتياط)
        if (!$user) {
            abort(401, 'Unauthenticated.');
        }

        // ليس متطوعًا → تجاهل
        if (!$user->volunteerProfile) {
            return $next($request);
        }

        $profile = $user->volunteerProfile;

        if ($profile->status !== 'active') {
            abort(403, 'volunteer.inactive');
        }

        return $next($request);
    }
}
