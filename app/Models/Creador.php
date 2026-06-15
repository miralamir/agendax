<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Creador extends Model
{
    protected $guarded = [];
    protected $table = 'creadores';

    public function eventos()
    {
        return Evento::whereJsonContains('bios', ['nombre' => $this->nombre])->get();
    }
}
