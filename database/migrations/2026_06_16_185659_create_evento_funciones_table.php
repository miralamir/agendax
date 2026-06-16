<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evento_funciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evento_id')->constrained('eventos')->onDelete('cascade');
            $table->date('fecha');
            $table->time('hora')->nullable();
            $table->string('nota')->nullable();
            $table->timestamps();

            $table->index(['evento_id', 'fecha']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evento_funciones');
    }
};
