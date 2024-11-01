<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tracklist', function (Blueprint $table) {
            $table->id('idTracklist');
            $table->string('name');
            $table->foreignId('thumbnail')->constrained(
                table: 'thumbnail',
                column: 'idThumbnail'
            );
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracklist');
    }
};
