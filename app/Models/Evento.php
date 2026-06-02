<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'startDate' => 'datetime',
        'endDate' => 'datetime',
        'inaugurationDate' => 'datetime',
        'singleDate' => 'datetime',
        'artists' => 'array',
        'curators' => 'array',
        'gallery' => 'array',
        'isPublished' => 'boolean',
        'isFeatured' => 'boolean',
    ];
}
