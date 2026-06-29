<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Novedad extends Model
{
    use HasFactory;

    protected $table = 'novedades'; // Especificar el nombre de la tabla

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'body',
        'bios',
        'image',
        'gallery',
        'videos',
        'pdf',
        'category',
        'subCategory',
        'author',
        'isPublished',
        'isFeatured',
        'seo_title',
        'seo_description',
        'published_at',
    ];

    protected $casts = [
        'gallery' => 'array',
        'videos' => 'array',
        'bios' => 'array',
        'isPublished' => 'boolean',
        'isFeatured' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Automatically generate slug from title if not provided.
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($novedad) {
            if (empty($novedad->slug) && !empty($novedad->title)) {
                $novedad->slug = Str::slug($novedad->title);
            }
        });

        static::updating(function ($novedad) {
            if (empty($novedad->slug) && !empty($novedad->title)) {
                $novedad->slug = Str::slug($novedad->title);
            }
        });
    }
}
