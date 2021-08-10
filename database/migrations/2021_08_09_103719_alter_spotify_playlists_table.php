<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSpotifyPlaylistsTable extends Migration
{
    public function up()
    {
        Schema::table('spotify_playlists', function (Blueprint $table) {
            $table->unsignedBigInteger('local_user_id')->nullable();
            $table->foreign('local_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('spotify_playlists', function (Blueprint $table) {
            $table->dropForeign('');
            $table->dropColumn('local_user_id');
        });
    }
}
