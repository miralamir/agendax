<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('titulo')->nullable();        // nombre interno para identificarlo en el panel
            $table->string('posicion');                  // home_hero_izq, home_post_destacados, etc.
            $table->string('imagen');                    // ruta de la imagen del banner
            $table->string('link')->nullable();          // a donde lleva al hacer clic
            $table->boolean('nueva_pestana')->default(true); // abrir el link en pestaña nueva
            $table->boolean('activo')->default(true);
            $table->timestamp('desde')->nullable();      // vigencia opcional
            $table->timestamp('hasta')->nullable();
            $table->unsignedInteger('clics')->default(0); // contador de clics (metrica simple)
            $table->unsignedInteger('impresiones')->default(0); // contador de vistas
            $table->timestamps();
            $table->index(['posicion', 'activo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
