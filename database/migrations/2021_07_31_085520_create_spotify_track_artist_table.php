<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpotifyTrackArtistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spotify_track_artist', function (Blueprint $table) {
            $table->string('track_id');
            $table->foreign('track_id')->references('id')->on('spotify_tracks')->onDelete('cascade');
            $table->string('artist_id');
            $table->foreign('artist_id')->references('id')->on('spotify_artists')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('spotify_track_artist');
    }
}
