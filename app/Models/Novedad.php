<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Novedad extends Model
{
    protected $table = 'novedades';
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'excerpt', 'body', 'image', 'gallery', 'videos',
        'pdf', 'category', 'subCategory', 'author', 'isPublished',
        'isFeatured', 'seo_title', 'seo_description', 'published_at'
    ];

    protected $casts = [
        'gallery' => 'array',
        'videos' => 'array',
        'isPublished' => 'boolean',
        'isFeatured' => 'boolean',
        'published_at' => 'datetime',
    ];

    // Auto-generar slug desde title
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($novedad) {
            if (empty($novedad->slug)) {
                $novedad->slug = Str::slug($novedad->title);
            }
        });
    }
}
