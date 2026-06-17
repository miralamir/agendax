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
        'bios' => 'array',
        'videos' => 'array',
        'funciones_regla' => 'array',
        'isPublished' => 'boolean',
        'isFeatured' => 'boolean',
    ];


    /**
     * Devuelve un texto resumido de las funciones a partir de la regla.
     * Ej: "Lunes y viernes 20:00 hs · Domingos 21:00 hs"
     */
    public function horarioResumido()
    {
        $regla = $this->funciones_regla;
        if (empty($regla) || empty($regla['dias'])) return null;

        $nombres = ['Domingos','Lunes','Martes','Miércoles','Jueves','Viernes','Sábados'];
        $ordenSemana = [1,2,3,4,5,6,0]; // lunes primero, domingo ultimo

        $horarios = $regla['horarios'] ?? [];
        $dias = array_map('intval', (array) $regla['dias']);

        // Agrupar dias por horario
        $grupos = [];
        foreach ($dias as $d) {
            $h = $horarios[(string)$d] ?? ($horarios['general'] ?? null);
            $h = $h ? substr($h, 0, 5) : 's/h';
            $grupos[$h][] = $d;
        }

        $partes = [];
        foreach ($grupos as $hora => $diasGrupo) {
            // ordenar los dias segun orden semanal
            usort($diasGrupo, fn($a,$b) => array_search($a,$ordenSemana) <=> array_search($b,$ordenSemana));
            $labels = array_map(fn($d) => $nombres[$d], $diasGrupo);
            // unir: "Lunes, Miércoles y Viernes"
            if (count($labels) > 1) {
                $ultimo = array_pop($labels);
                $texto = implode(', ', $labels) . ' y ' . $ultimo;
            } else {
                $texto = $labels[0];
            }
            $partes[] = $hora === 's/h' ? $texto : "$texto $hora hs";
        }

        return implode(' · ', $partes);
    }

    public function funciones()
    {
        return $this->hasMany(EventoFuncion::class, 'evento_id')->orderBy('fecha')->orderBy('hora');
    }
}
