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
        Schema::create('tracklist_track', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idTracklist')->constrained(
                table: 'tracklist',
                column: 'idTracklist',
                indexName: 'idTracklist'
            );
            $table->foreignId('idTrack')->constrained(
                table: 'track',
                column: 'idTrack'
            );
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracklist_track');
    }
};
