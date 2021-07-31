<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpotifyAlbumArtistTable extends Migration
{
    public function up()
    {
        Schema::create('spotify_album_artist', function (Blueprint $table) {
            $table->string('album_id');
            $table->foreign('album_id')->references('id')->on('spotify_albums')->onDelete('cascade');
            $table->string('artist_id');
            $table->foreign('artist_id')->references('id')->on('spotify_artists')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('spotify_album_artist');
    }
}
