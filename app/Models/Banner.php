<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Banner extends Model
{
    protected $table = 'banners';
    protected $fillable = [
        'titulo', 'posicion', 'imagen', 'link', 'nueva_pestana',
        'activo', 'desde', 'hasta', 'clics', 'impresiones',
    ];

    protected $casts = [
        'nueva_pestana' => 'boolean',
        'activo' => 'boolean',
        'desde' => 'datetime',
        'hasta' => 'datetime',
    ];

    public function posiciones()
    {
        return $this->hasMany(BannerPosicion::class);
    }

    // Devuelve array de strings con las zonas asignadas
    public function listaPosiciones(): array
    {
        return $this->posiciones()->pluck('posicion')->all();
    }

    // Elige un banner al azar entre los activos y vigentes de una posición
    public static function paraPosicion(string $posicion): ?Banner
    {
        $ahora = Carbon::now();

        $banner = static::where('activo', true)
            ->whereHas('posiciones', fn($q) => $q->where('posicion', $posicion))
            ->where(fn($q) => $q->whereNull('desde')->orWhere('desde', '<=', $ahora))
            ->where(fn($q) => $q->whereNull('hasta')->orWhere('hasta', '>=', $ahora))
            ->inRandomOrder()
            ->first();

        if ($banner) {
            static::where('id', $banner->id)->increment('impresiones');
        }

        return $banner;
    }
}
