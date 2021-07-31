<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpotifyArtistsTable extends Migration
{
    public function up()
    {
        Schema::create('spotify_artists', function (Blueprint $table) {
            $table->string('id')->unique()->primary()->index();
            $table->json('followers')->nullable();
            $table->json('genres')->nullable();
            $table->string('href')->nullable();
            $table->json('images')->nullable();
            $table->string('name')->nullable();
            $table->unsignedTinyInteger('popularity')->nullable();
            $table->string('type')->default('artist');
            $table->string('uri')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('spotify_artists');
    }
}
