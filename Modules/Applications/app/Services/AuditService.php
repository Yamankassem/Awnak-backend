<?php
namespace Modules\Applications\Services;

class AuditService {
    public function log(string $action, string $model, $user, array $data = []) {
        \DB::table('audit_logs')->insert([
            'user_id' => $user?->id,
            'action' => $action,
            'model' => $model,
            'data' => json_encode($data),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }
}