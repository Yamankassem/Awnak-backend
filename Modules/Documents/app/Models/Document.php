<?php

namespace Modules\Documents\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Documents\Database\Factories\DocumentFactory;

class Document extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    // protected static function newFactory(): DocumentFactory
    // {
    //     // return DocumentFactory::new();
    // }
}
