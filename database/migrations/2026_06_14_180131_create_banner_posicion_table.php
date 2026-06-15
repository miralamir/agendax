<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banner_posicion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('banner_id')->constrained()->onDelete('cascade');
            $table->string('posicion');
            $table->timestamps();
            $table->unique(['banner_id', 'posicion']);
            $table->index('posicion');
        });

        // Migrar los banners existentes: copiar su posicion actual a la tabla nueva
        $banners = DB::table('banners')->whereNotNull('posicion')->get();
        foreach ($banners as $b) {
            DB::table('banner_posicion')->insert([
                'banner_id' => $b->id,
                'posicion' => $b->posicion,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('banner_posicion');
    }
};
