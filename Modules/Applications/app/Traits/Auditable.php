<?php

namespace Modules\Applications\Traits;

trait Auditable 
{
    public static function bootAuditable()
    {
        static::created(function ($model){
            $model->audit('created', $model->getAttributes());
        });

        static::updated(function ($model){
            $model->audit('updated', $model->getChange(), $model->getOriginal());
        });

        static::deleted(function ($model){
            $model->audit('deleted', $model->getAttributes());
        });
    }


    protected function audit(string $action, array $newData, array $oldData = [])
    {
        \DB::table(audit_logs)->insert([
            'user_id'       => auth()->id(),
            'action'        => $action,
            'model_type'    => get_class($this),
            'model_id'      => $this->id,
            'old_data'      => json_encode($oldData),
            'new_data'      => json_encode($newData),
            'ip_address'    => request()->ip(),
            'user_agent'    => request()->userAgent,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }
}
