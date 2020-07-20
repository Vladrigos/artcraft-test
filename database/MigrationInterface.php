<?php

namespace Database;

interface MigrationInterface
{
    public function up() : void;
    public function down() : void;
}