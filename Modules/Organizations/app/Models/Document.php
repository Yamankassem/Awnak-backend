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

    #<----------------------------Scopes----------------------------->
    /**
     * Scope a query to only include documents that belong to a specific organization.
     *
     * This scope filters documents through their related opportunity,
     * ensuring only those linked to the given organization ID are returned.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $organizationId The ID of the organization.
     * @return \Illuminate\Database\Eloquent\Builder
     *
     * Example:
     * Document::forOrganization(3)->get();
     * // Returns all documents where opportunity.organization_id = 3
     */
    public function scopeForOrganization($query, $organizationId)
    {
        return $query->whereHas(
            'opportunity',
            function ($q) use ($organizationId) {
                $q->where('organization_id', $organizationId);
            }
        );
    }
    /**
     * Scope a query to only include documents owned by a specific user.
     *
     * This scope traverses the relationship chain (document → opportunity → organization)
     * and filters documents based on the organization owner (user_id).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $userId The ID of the user who owns the organization.
     * @return \Illuminate\Database\Eloquent\Builder
     *
     * Example:
     * Document::ownedByUser(5)->get();
     * // Returns all documents where opportunity.organization.user_id = 5
     */

    public function scopeOwnedByUser($query, $userId)
    {
        return $query->whereHas(
            'opportunity.organization',
            function ($q) use ($userId) {
                $q->where('user_id', $userId);
            }
        );
    }
}
