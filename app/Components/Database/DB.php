<?php

namespace App\Components\Database;

use Illuminate\Database\Capsule\Manager as Capsule;

class DB
{
    private Capsule $capsule;
    private Array $config;

    public function __construct(Capsule $capsule, array $config)
    {
        $this->capsule = $capsule;
        $this->config = $config;

        $this->addConnection();
        $this->setAsGlobal();
        $this->bootEloquent();
    }

    private function addConnection() : void
    {
        $this->capsule->addConnection([
            'driver'    => $this->config['DB_CONNECTION'],
            'host'      => $this->config['DB_HOST'],
            'database'  => $this->config['DB_DATABASE'],
            'username'  => $this->config['DB_USERNAME'],
            'password'  => $this->config['DB_PASSWORD'],
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'prefix'    => '',
        ]);
    }

    // Setup the Eloquent ORM
    private function bootEloquent() : void
    {
        $this->capsule->bootEloquent();
    }

    //Make this capsule instance available globally.
    private function setAsGlobal() : void
    {
        $this->capsule->setAsGlobal();
    }
}