<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('creadores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('slug')->unique();
            $table->string('rol')->nullable();
            $table->string('foto')->nullable();
            $table->text('bio')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('creadores');
    }
};
