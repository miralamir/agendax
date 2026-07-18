<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Post para Instagram generado por IA (n8n). Solo se usa en el dashboard:
     * no se renderiza en el frontend público. Va en los dos tipos de contenido
     * que tiene bamarte, eventos y novedades.
     */
    public function up(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->text('instagram_post')->nullable();
        });

        Schema::table('novedades', function (Blueprint $table) {
            $table->text('instagram_post')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->dropColumn('instagram_post');
        });

        Schema::table('novedades', function (Blueprint $table) {
            $table->dropColumn('instagram_post');
        });
    }
};
