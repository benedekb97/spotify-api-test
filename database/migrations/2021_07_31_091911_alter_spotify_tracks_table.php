<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSpotifyTracksTable extends Migration
{
    public function up()
    {
        Schema::table('spotify_tracks', function (Blueprint $table) {
            $table->string('album_id')->nullable();
            $table->foreign('album_id')->references('id')->on('spotify_albums')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('spotify_tracks', function (Blueprint $table) {
            $table->dropForeign('spotify_tracks_album_id_foreign');
            $table->dropColumn('album_id');
        });
    }
}
