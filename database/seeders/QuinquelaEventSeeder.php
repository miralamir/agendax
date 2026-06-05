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
            ['title' => 'Nuevas Muestras en el Museo Quinquela Martín: Pérez Esquivel, Ferrari y Pisani'],
            [
                'category' => 'Artes Visuales',
                'singleDate' => Carbon::createFromFormat('Y-m-d H:i', "2026-06-06 14:00"),
                'locationName' => 'Museo Benito Quinquela Martín',
                'venueAddress' => 'Av. Pedro de Mendoza 1835, La Boca, Buenos Aires',
                'priceInfo' => 'General: $10.000 | Residentes: $2.000 | Miércoles GRATIS.',
                'mainImageUrl' => 'https://upload.wikimedia.org/wikipedia/commons/e/e0/Museo_Quinquela_Mart%C3%ADn.JPG',
                'description' => 'El icónico museo de La Boca inaugura tres exhibiciones simultáneas con obras de Adolfo Pérez Esquivel, José “Pipo” Ferrari y Sergio Pisani.',
                'isFeatured' => 1,
                'isPublished' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
