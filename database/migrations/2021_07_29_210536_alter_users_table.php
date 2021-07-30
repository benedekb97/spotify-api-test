<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('spotify_access_token')->nullable();
            $table->string('spotify_refresh_token')->nullable();
            $table->dateTime('spotify_access_token_expiry')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('spotify_access_token');
            $table->dropColumn('spotify_refresh_token');
            $table->dropColumn('spotify_access_token_expiry');
        });
    }
}