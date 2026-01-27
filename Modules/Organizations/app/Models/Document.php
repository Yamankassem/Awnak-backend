<?php

namespace Modules\Organizations\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Organizations\Database\Factories\DocumentFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Document extends Model implements HasMedia
{
    use HasFactory, LogsActivity, InteractsWithMedia;

    protected $fillable = [
        'opportunity_id',
        'title',
        'description',
    ];

    protected static function newFactory()
    {
        return DocumentFactory::new();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('document')
            ->logOnly(['title', 'description'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Document has been {$eventName}");
    }

    public function opportunity()
    {
        return $this->belongsTo(Opportunity::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('documents');
    }
}
