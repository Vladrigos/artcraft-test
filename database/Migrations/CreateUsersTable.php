<?php

namespace Database\Migrations;

use Illuminate\Database\Capsule\Manager as Capsule;
use Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Capsule::schema()->create('users', function ($table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('email')->unique();
            $table->string('photo')->nullable();
            $table->string('key')->nullable()->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Capsule::schema()->dropIfExists('users');
    }
}