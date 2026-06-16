<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventoFuncion extends Model
{
    use HasFactory;

    protected $table = 'evento_funciones';
    protected $guarded = [];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }
}
