<?php
/*
 * for command line.
 * composer migrate
 */

require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Components\Database\DB;
use Database\Migrations;

$config = require __DIR__ . '/../config/database.php';
$capsule = new Capsule;

$db = new DB($capsule, $config);

$directory = __DIR__ . '/Migrations';
$scanned_directory = array_diff(scandir($directory), array('..', '.'));

foreach ($scanned_directory as $migrationName)
{
    echo $migrationName . "\n" . "\n";
    $path = "Database\Migrations\\" . $migrationName;
    $classname = str_replace(".php", "", $path);

    $migration = new $classname();
    $migration->down();
    $migration->up();
}

