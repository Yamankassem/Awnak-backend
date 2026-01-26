<?

namespace Modules\Organizations\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Organizations\Database\Factories\DocumentFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Model: Document
 *
 * Represents a document entity that can be attached to an opportunity.
 * Integrates with Spatie Media Library to handle file uploads and storage.
 */
class Document extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return DocumentFactory::new();
    }

    /**
     * The attributes that are mass assignable.
     *
     * These fields can be filled when creating or updating a document.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'opportunity_id',
        'title',
        'description',
    ];

    /**
     * Relationship: A document belongs to a single opportunity.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function opportunity()
    {
        return $this->belongsTo(Opportunity::class);
    }

    /**
     * Register media collections for the document.
     *
     * Defines the "documents" collection where uploaded files are stored.
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('documents');
    }
}
