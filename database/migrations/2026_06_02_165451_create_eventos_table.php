<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eventos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('description')->nullable();
            $table->string('category')->nullable();
            $table->string('subCategory')->nullable();
            
            $table->dateTime('startDate')->nullable();
            $table->dateTime('endDate')->nullable();
            $table->dateTime('inaugurationDate')->nullable();
            $table->dateTime('singleDate')->nullable();
            $table->string('recurringSchedule')->nullable();
            
            $table->string('artist')->nullable();
            $table->json('artists')->nullable();
            $table->text('artistBio')->nullable();
            $table->string('curator')->nullable();
            $table->json('curators')->nullable();
            
            $table->string('locationName')->nullable();
            $table->string('venueAddress')->nullable();
            $table->string('room')->nullable();
            
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lng', 10, 8)->nullable();
            
            $table->string('venueHours')->nullable();
            $table->string('priceInfo')->nullable();
            $table->string('venuePhone')->nullable();
            $table->string('venueEmail')->nullable();
            $table->string('venueWebsite')->nullable();
            $table->string('venueSocial')->nullable();
            
            $table->string('mainImageUrl')->nullable();
            $table->string('secondaryImageUrl')->nullable();
            $table->string('artistImageUrl')->nullable();
            $table->json('gallery')->nullable();
            $table->string('catalogPdfUrl')->nullable();
            $table->string('ticketUrl')->nullable();
            
            $table->boolean('isPublished')->default(false);
            $table->boolean('isFeatured')->default(false);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eventos');
    }
};
