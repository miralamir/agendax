<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannerPosicion extends Model
{
    protected $table = 'banner_posicion';
    protected $fillable = ['banner_id', 'posicion'];

    public function banner()
    {
        return $this->belongsTo(Banner::class);
    }
}
