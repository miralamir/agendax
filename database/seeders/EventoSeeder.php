<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Evento;

class EventoSeeder extends Seeder
{
    public function run(): void
    {
        Evento::factory()->count(10)->create();
    }
}
