<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpotifyTracksTable extends Migration
{
    public function up()
    {
        Schema::create('spotify_tracks', function (Blueprint $table) {
            $table->string('id')->unique()->primary()->index();
            $table->json('available_markets')->nullable();
            $table->unsignedTinyInteger('disc_number')->nullable();
            $table->bigInteger('duration_ms')->nullable();
            $table->boolean('explicit')->default(false);
            $table->json('external_ids')->nullable();
            $table->json('external_urls')->nullable();
            $table->string('href')->nullable();
            $table->boolean('is_local')->nullable();
            $table->boolean('is_playable')->nullable();
            $table->string('name');
            $table->unsignedTinyInteger('popularity');
            $table->string('preview_url')->nullable();
            $table->unsignedMediumInteger('track_number')->nullable();
            $table->string('type')->nullable();
            $table->string('uri')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('spotify_tracks');
    }
}
