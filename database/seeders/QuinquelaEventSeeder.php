<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Evento;
use Illuminate\Support\Carbon;

class QuinquelaEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Evento::updateOrCreate(
            ['title' => 'Nuevas exposiciones: Adolfo Pérez Esquivel, José “Pipo” Ferrari y Sergio Pisani.'],
            [
                'title' => 'Nuevas exposiciones: Adolfo Pérez Esquivel, José “Pipo” Ferrari y Sergio Pisani.',
                'artists' => json_encode(['Adolfo Pérez Esquivel', 'José “Pipo” Ferrari', 'Sergio Pisani']),
                'curators' => json_encode(['Juan José Cano (muestra Ferrari)', 'Pedro Lorenzo Lespada (muestra Pisani)']),
                'description' => 'El Museo Quinquela Martín presenta tres nuevas exposiciones que reúnen miradas sobre la identidad argentina en La Boca: "María del arrabal" de Adolfo Pérez Esquivel (con apoyo de la Fundación Piazzolla), un homenaje retrospectivo al maestro José “Pipo” Ferrari, y "En la bajante", un recorrido fotográfico y pictórico por los últimos 25 años de carrera de Sergio Pisani.',
                'artistBio' => json_encode([
                    'Adolfo Pérez Esquivel' => '(Buenos Aires, 1931). Premio Nobel de la Paz en 1980. Desarrolló una intensa actividad en exposiciones, murales y monumentos internacionales.',
                    'José “Pipo” Ferrari' => '(Milán, 1930 - Buenos Aires, 1995). Desarrolló una visión pictórica constructivista y se destacó por su obra centrada en el noroeste nacional y su gran labor docente.',
                    'Sergio Pisani' => '(Buenos Aires, 1964). Pintor y fotógrafo enfocado en asuntos barriales desde una perspectiva crítica y alejada de la imagen turística.'
                ]),
                'mainImageUrl' => null, // Pendiente de carga
                'secondaryImageUrl' => null, // Pendiente de carga
                'artistImageUrl' => null, // Pendiente de carga
                'gallery' => null, // Pendiente de carga
                'category' => 'Arte',
                'subCategory' => 'Artes Visuales / Pintura / Fotografía',
                'isPublished' => 1,
                'isFeatured' => 1,
                'inaugurationDate' => Carbon::createFromFormat('Y-m-d H:i', "2026-06-06 14:00"),
                'singleDate' => null, // Día Único: No.
                'startDate' => Carbon::createFromFormat('Y-m-d', "2026-06-06"),
                'endDate' => null, // No especificada en prensa
                'venueHours' => 'Lunes de 11 a 16 h. Miércoles a domingos de 11 a 18 h. Martes cerrado.',
                'locationName' => 'Museo de Bellas Artes de Artistas Argentinos Benito Quinquela Martín',
                'room' => 'Múltiples salas.',
                'venueAddress' => 'Av. Pedro de Mendoza 1835, La Boca, Buenos Aires',
                'lat' => null, // Autocompletar vía mapa
                'lng' => null, // Autocompletar vía mapa
                'venuePhone' => null, // No especificado
                'venueEmail' => null, // No especificado
                'venueWebsite' => null, // No especificado
                'venueSocial' => null, // No especificado
                'priceInfo' => 'Entrada general: $10.000. Residentes argentinos/extranjeros con DNI: $2.000. Miércoles sin cargo. Sin cargo todos los días para: Jubilados, excombatientes, estudiantes universitarios, personas con discapacidad, menores de 12 años y colegios públicos.',
                'ticketUrl' => null, // No especificada
                'catalogPdfUrl' => null, // No especificada
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
