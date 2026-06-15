<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Evento;
use App\Models\Creador;
use Illuminate\Support\Str;

class SyncCreadores extends Command
{
    protected $signature = 'creadores:sync';
    protected $description = 'Crea/actualiza creadores desde las bios y el campo artist de los eventos publicados';

    public function handle()
    {
        $creados = 0;
        $eventos = Evento::where('isPublished', 1)->get();

        foreach ($eventos as $evento) {
            // Desde bios (con bio completa)
            foreach ($evento->bios ?? [] as $bio) {
                $nombre = trim($bio['nombre'] ?? '');
                if ($nombre && $this->upsert($nombre, $bio['rol'] ?? null, $bio['foto'] ?? null, $bio['bio'] ?? null)) $creados++;
            }

            // Desde el campo artist (separando "X y Z", "X, Z", "X & Z")
            if (!empty($evento->artist)) {
                $nombres = preg_split('/\s*(?:,|\sy\s|&)\s*/u', $evento->artist);
                foreach ($nombres as $n) {
                    $n = trim($n);
                    if ($n && strlen($n) > 1 && $this->upsert($n, 'Artista', null, null)) $creados++;
                }
            }

            // Desde el campo curator (curadores)
            if (!empty($evento->curator)) {
                $nombres = preg_split('/\s*(?:,|\sy\s|&)\s*/u', $evento->curator);
                foreach ($nombres as $n) {
                    $n = trim($n);
                    if ($n && strlen($n) > 1 && $this->upsert($n, 'Curador/a', null, null)) $creados++;
                }
            }
        }

        $this->info("Sincronización completa. Creadores nuevos: {$creados}. Total en base: " . Creador::count());
        return 0;
    }

    private function upsert($nombre, $rol, $foto, $bio): bool
    {
        $slug = Str::slug($nombre);
        if (!$slug) return false;
        $creador = Creador::firstOrNew(['slug' => $slug]);
        $esNuevo = !$creador->exists;
        $creador->nombre = $nombre;
        if (!empty($rol) && !$creador->rol) $creador->rol = $rol;
        if (!empty($foto) && !$creador->foto) $creador->foto = $foto;
        if (!empty($bio) && !$creador->bio) $creador->bio = $bio;
        $creador->save();
        return $esNuevo;
    }
}
