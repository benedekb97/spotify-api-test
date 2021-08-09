<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpotifyPlaylistTrackTable extends Migration
{
    public function up()
    {
        Schema::create('spotify_playlist_track', function (Blueprint $table) {
            $table->string('playlist_id');
            $table->foreign('playlist_id')->references('id')->on('spotify_playlists')->onDelete('cascade');
            $table->string('track_id');
            $table->foreign('track_id')->references('id')->on('spotify_tracks')->onDelete('cascade');
            $table->dateTime('added_at')->nullable();
            $table->string('added_by_user_id')->nullable();
            $table->boolean('is_local')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('spotify_playlist_track');
    }
}
