<?php

namespace Modules\Evaluations\Services;

use Modules\Evaluations\Models\Certificate;
use Illuminate\Support\Facades\Auth;

class CertificateService
{
    /**
     * Create certificate
     */
    public function createCertificate(array $data): Certificate
    {
        if (!Auth::check()) {
            throw new \Exception('Unauthenticated', 401);
        }

        $data['issued_at'] = $data['issued_at'] ?? now();

        return Certificate::create($data);
    }

    /**
     * Update certificate
     */
    public function updateCertificate(Certificate $certificate, array $data): Certificate
    {
        if (!Auth::check()) {
            throw new \Exception('Unauthenticated', 401);
        }

        $certificate->update($data);
        return $certificate;
    }

    /**
     * Get certificate by id
     */
    public function getById(int $id): Certificate
    {
        return Certificate::findOrFail($id);
    }

    /**
     * Get certificates by task
     */
    public function getByTask(int $taskId)
    {
        return Certificate::where('task_id', $taskId)->latest()->get();
    }

    /**
     * Delete certificate
     */
    public function deleteCertificate(Certificate $certificate): bool
    {
        if (!Auth::check()) {
            throw new \Exception('Unauthenticated', 401);
        }

        $certificate->delete();
        return true;
    }
}
