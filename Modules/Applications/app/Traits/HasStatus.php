<?php

namespace Modules\Applications\Traits;

trait HasStatus
{
    public function changeStatus(string $status): bool
    {
        if (!in_array($status, $this->getAllowedStatuses()))
            throw new Exception("Invalid status: {$status}");
            
        $oldStatus = $this->status;
        $this->status = $status;

        if ($this->save())
            {
                $this->logStatusChange($oldStatus, $status);
                return true;
            }
            return false;
    }

    abstract public function getAllowedStatuses(): array;

    protected function logStatusChange(string $oldStatus, string $newStatus): void
    {
        \Log::info('Status change', [
            'model' => get_class($this),
            'id' => $this->id,
            'from' => $oldStatus,
            'to' => $newStatus,
            'user_id' => auth()->id(),

        ]);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
}
