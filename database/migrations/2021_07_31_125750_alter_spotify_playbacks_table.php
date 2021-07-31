<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSpotifyPlaybacksTable extends Migration
{
    public function up()
    {
        Schema::table('spotify_playbacks', function (Blueprint $table) {
            $table->dateTime('played_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('spotify_playbacks', function (Blueprint $table) {
            $table->dropColumn('played_at');
        });
    }
}
