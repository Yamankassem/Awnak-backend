<?php

namespace Modules\Organizations\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Modules\Organizations\Database\Factories\DocumentFactory;
class Document extends Model
{
    use HasFactory;

    protected static function newFactory()
    { return DocumentFactory::new(); }

    /**
     * The attributes that are mass assignable.
     *
     * These fields can be filled when creating or updating a document.
     */
    protected $fillable = [
        'opportunity_id',
        'title',
        'file_path',
        'file_type',
        'file_size',
    ];

    /**
     * Relationship: Document belongs to an Opportunity.
     *
     * Each document is linked to a single opportunity.
     */
    public function opportunity()
    {
        return $this->belongsTo(Opportunity::class);
    }
}
