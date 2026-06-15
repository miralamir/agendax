<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lugar extends Model
{
    protected $guarded = [];
    protected $table = 'lugares';

    public function eventos()
    {
        return Evento::where('locationName', $this->nombre)->where('isPublished', 1)->orderBy('startDate', 'desc')->get();
    }
}
