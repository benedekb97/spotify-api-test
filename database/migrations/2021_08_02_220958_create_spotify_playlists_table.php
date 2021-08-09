<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpotifyPlaylistsTable extends Migration
{
    public function up()
    {
        Schema::create('spotify_playlists', function (Blueprint $table) {
            $table->string('id')->unique()->primary()->index();
            $table->boolean('collaborative')->nullable();
            $table->string('description')->nullable();
            $table->json('external_url')->nullable();
            $table->json('followers')->nullable();
            $table->string('href')->nullable();
            $table->json('images')->nullable();
            $table->string('name');
            $table->string('owner_user_id')->nullable();
            $table->string('snapshot_id')->nullable();
            $table->string('type');
            $table->string('uri');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('spotify_playlists');
    }
}
