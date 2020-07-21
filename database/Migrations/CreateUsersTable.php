<?php

namespace Database\Migrations;

use Illuminate\Database\Capsule\Manager as Capsule;
use Database\Migration;

class CreateUsersTable extends Migration
{
    public function up() : void
    {
        Capsule::schema()->create('users', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('api_token')->nullable();
            $table->timestamp('token_end')->nullable();
            $table->string('user_hash')->nullable();
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    public function down() : void
    {
        Capsule::schema()->dropIfExists('users');
    }
}