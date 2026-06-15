<?php

namespace App\Observers;

use App\Models\Evento;
use App\Models\Creador;
use Illuminate\Support\Str;

class EventoObserver
{
    public function saved(Evento $evento): void
    {
        foreach ($evento->bios ?? [] as $bio) {
            $nombre = trim($bio['nombre'] ?? '');
            if ($nombre) $this->upsert($nombre, $bio['rol'] ?? null, $bio['foto'] ?? null, $bio['bio'] ?? null);
        }

        if (!empty($evento->artist)) {
            foreach (preg_split('/\s*(?:,|\sy\s|&|\|)\s*/u', $evento->artist) as $n) {
                $n = trim($n);
                if (strlen($n) > 1) $this->upsert($n, 'Artista', null, null);
            }
        }
    }

    private function upsert($nombre, $rol, $foto, $bio): void
    {
        $slug = Str::slug($nombre);
        if (!$slug) return;
        $creador = Creador::firstOrNew(['slug' => $slug]);
        $creador->nombre = $nombre;
        if (!empty($rol) && !$creador->rol) $creador->rol = $rol;
        if (!empty($foto) && !$creador->foto) $creador->foto = $foto;
        if (!empty($bio) && !$creador->bio) $creador->bio = $bio;
        $creador->save();
    }
}
