<?php

namespace Database;

interface MigrationInterface
{
    public function up();
    public function down();
}