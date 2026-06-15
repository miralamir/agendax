<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comentarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->morphs('comentable'); // comentable_id + comentable_type
            $table->text('body');
            $table->boolean('oculto')->default(false); // moderacion manual de un comentario puntual
            $table->timestamps();
            $table->index(['comentable_id', 'comentable_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comentarios');
    }
};
