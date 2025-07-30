<?php

// load our environment files - used to store credentials & configuration
define('FCPATH', dirname(__FILE__).DIRECTORY_SEPARATOR);
if (file_exists(FCPATH . '/vendor/autoload.php')) {
    require_once(FCPATH . '/vendor/autoload.php');
}
$dotenv = Dotenv\Dotenv::createImmutable(FCPATH);
$dotenv->load();

return
    [
        'paths' => [
            'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
            'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds'
        ],
        'environments' =>
            [
                'default_database' => 'development',
                'default_migration_table' => 'phinx_migrations',
                'development'      =>
                    [
                        'adapter' => 'mysql',
                        'host' => $_ENV['DB_HOST'],
                        'name' => $_ENV['DB_DATABASE'],
                        'user' => $_ENV['DB_USERNAME'],
                        'pass' => $_ENV['DB_PASSWORD'],
                        'port' => 3306,
                        'charset' => 'utf8',
                        'collation' => 'utf8_unicode_ci',
                    ],
                'staging' =>
                    [
                        'adapter' => 'mysql',
                        'host' => $_ENV['DB_HOST'],
                        'name' => $_ENV['DB_DATABASE'],
                        'user' => $_ENV['DB_USERNAME'],
                        'pass' => $_ENV['DB_PASSWORD'],
                        'port' => 3306,
                        'charset' => 'utf8',
                        'collation' => 'utf8_unicode_ci',
                    ],
                'production' =>
                    [
                        'adapter' => 'mysql',
                        'host' => $_ENV['DB_HOST'],
                        'name' => $_ENV['DB_DATABASE'],
                        'user' => $_ENV['DB_USERNAME'],
                        'pass' => $_ENV['DB_PASSWORD'],
                        'port' => 3306,
                        'charset' => 'utf8',
                        'collation' => 'utf8_unicode_ci',
                    ],
            ],
    ];
