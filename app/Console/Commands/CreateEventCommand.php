<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Evento;
use Illuminate\Support\Carbon;

class CreateEventCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'evento:crear 
                            {--titulo= : El título del evento}
                            {--categoria= : La categoría del evento}
                            {--fecha= : La fecha del evento (YYYY-MM-DD)}
                            {--hora= : La hora del evento (HH:MM)}
                            {--lugar= : El nombre del lugar}
                            {--direccion= : La dirección del lugar}
                            {--costo= : Información del costo}
                            {--imagen= : URL de la imagen principal}
                            {--descripcion= : Descripción completa del evento}
                            {--destacado : Marcar como evento destacado}
                            {--publicado : Publicar inmediatamente}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea un nuevo evento en la base de datos de BAMARTE';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $evento = new Evento();

            $evento->title = $this->option('titulo');
            $evento->category = $this->option('categoria');
            
            if ($this->option('fecha')) {
                $fecha = $this->option('fecha');
                $hora = $this->option('hora') ?: '00:00';
                // Asumo que el modelo tiene 'singleDate', si no, esto se puede adaptar.
                $evento->singleDate = Carbon::createFromFormat('Y-m-d H:i', "$fecha $hora");
            }

            $evento->locationName = $this->option('lugar');
            $evento->locationAddress = $this->option('direccion');
            $evento->cost = $this->option('costo');
            $evento->mainImageUrl = $this->option('imagen');
            $evento->description = $this->option('descripcion');
            $evento->is_featured = $this->option('destacado');
            $evento->is_published = $this->option('publicado');
            
            // Asignamos un user_id por defecto. Esto debería ajustarse si hay un sistema de usuarios.
            $evento->user_id = 1;

            $evento->save();

            $this->info("¡Evento '{$evento->title}' creado exitosamente con ID: {$evento->id}!");

            return 0;
        } catch (\Exception $e) {
            $this->error("Error al crear el evento: " . $e->getMessage());
            return 1;
        }
    }
}
