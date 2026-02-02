<?php

namespace Modules\Evaluations\Services;

use Modules\Evaluations\Models\Certificate;
use Illuminate\Support\Facades\Auth;
use Modules\Applications\Models\Task;
use Spatie\Activitylog\Models\Activity;

class CertificateServices
{
    /**
     * Create certificate
     */
    public function createCertificate(array $data): Certificate
    {
            $task = Task::find($data['task_id'] ?? null);
            if (!$task) {
                throw new \Exception('Task not found', 404);
            }
            if ($task->status !== 'complete') {
                throw new \Exception('Cannot create certificate: task is not completed', 403);
            }
        $data['issued_at'] = $data['issued_at'] ?? now();
        $user = Auth::user();
        $certificate= Certificate::create($data );
         Activity::create([
                            'log_name'     => 'audit',
                            'description'  => 'certificates.created',
                            'subject_type' => Certificate::class,
                            'subject_id'   => $certificate->id,
                            'causer_type'  => get_class($user),
                            'causer_id'    => $user->id,
                            'properties'   => [
                                                'certificate_id' => $certificate->id,
                                                'created_by' => $user->name,
                                              ],
                         ]);
        return $certificate;
    }

    /**
     * Update certificate
     */
    public function updateCertificate(Certificate $certificate, array $data): Certificate
    {
        $user = Auth::user();
        $certificate->update($data);
        Activity::create([
                            'log_name'     => 'audit',
                            'description'  => 'certificates.updated',
                            'subject_type' => Certificate::class,
                            'subject_id'   => $certificate->id,
                            'causer_type'  => get_class($user),
                            'causer_id'    => $user->id,
                            'properties'   => [
                                                'certificate_id' => $certificate->id,
                                                'updated_by' => $user->name,
                                              ],
                         ]);
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
    public function getAll(int $perPage =4)
    {
        
        $user = Auth::user();
        $query = Certificate::with('task.application.volunteer')
            ->latest();
        if ($user->hasRole('volunteer')) {
            $query->whereHas('task.application', function ($q) use ($user) {
                $q->where('volunteer_id', $user->id);
            });
        }

        return $query->paginate($perPage);
    }

    /**
     * Delete certificate
     */
    public function deleteCertificate(Certificate $certificate): void
    {
            $user = Auth::user();
            $certificateId   = $certificate->id;
            $certificate->delete();  
          Activity::create([
                            'log_name'     => 'audit',
                            'description'  => 'certificates.deleted',
                            'subject_type' => Certificate::class,
                            'subject_id'   => $certificateId,
                            'causer_type'  => get_class($user),
                            'causer_id'    => $user->id,
                            'properties'   => [
                                                'certificate_id' => $certificateId,
                                                'deleted_by' => $user->name,
                                              ],
        ]);
    }
}
