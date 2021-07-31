<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpotifyAlbumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spotify_albums', function (Blueprint $table) {
            $table->string('id')->unique()->primary()->index();
            $table->json('available_markets')->nullable();
            $table->json('copyrights')->nullable();
            $table->json('external_ids')->nullable();
            $table->json('external_urls')->nullable();
            $table->json('genres')->nullable();
            $table->string('href')->nullable();
            $table->json('images')->nullable();
            $table->string('label')->nullable();
            $table->string('name');
            $table->unsignedTinyInteger('popularity')->nullable();
            $table->string('release_date', 10)->nullable();
            $table->enum('release_date_precision', ['year', 'month', 'day'])->nullable();
            $table->json('restrictions')->nullable();
            $table->unsignedInteger('total_tracks')->nullable();
            $table->string('type')->default('album')->nullable();
            $table->string('uri')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('spotify_albums');
    }
}
