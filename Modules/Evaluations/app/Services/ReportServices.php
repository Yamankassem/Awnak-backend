<?php

namespace Modules\Evaluations\Services;

use Modules\Evaluations\Models\Report;
use Illuminate\Support\Facades\Auth;

class ReportService
{
    /**
     * Create report
     */
    public function createReport(array $data): Report
    {
        if (!Auth::check()) {
            throw new \Exception('Unauthenticated', 401);
        }

        $data['generated_by'] = Auth::id();
        $data['generated_at'] = now();

        return Report::create($data);
    }

    /**
     * Get report by id
     */
    public function getById(int $id): Report
    {
        return Report::findOrFail($id);
    }

    /**
     * Get reports by type
     */
    public function getByType(string $type)
    {
        return Report::where('report_type', $type)->latest()->get();
    }

    /**
     * Delete report
     */
    public function deleteReport(Report $report): bool
    {
        if (!Auth::check()) {
            throw new \Exception('Unauthenticated', 401);
        }

        $report->delete();
        return true;
    }
}
