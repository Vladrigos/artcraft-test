<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('users', function ($table) {
    $table->id();
    $table->string('name')->unique();
    $table->string('email')->unique();
    $table->string('photo')->nullable();
    $table->string('key')->nullable()->unique();
    $table->timestamps();
});