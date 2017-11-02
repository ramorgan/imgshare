<?php

namespace imgshare;

use Silex\Application as SilexApplication;
use Silex\Provider\DoctrineServiceProvider;
use Doctrine\DBAL\Connection;


class App extends SilexApplication
{
    public function __construct()
    {
        parent::__construct();

        $this->registerProviders($this);
        $this->registerRoutes($this);
    }

    protected function registerProviders(App $app)
    {
        $app->register(
            new DoctrineServiceProvider(),
            [
                'db.options' => [
                    'driver'   => 'pdo_mysql',
                    'dbname'   => 'default',
                    'host'     => 'db',
                    'user'     => 'user',
                    'password' => 'user',
                ],
            ]
        );
    }

    protected function registerRoutes(App $app)
    {
        $app->get(
            '/',
            function () use ($app) {
                return "Welcome to your new Silex Application!";
            }
        )->bind('homepage');

        $app->get('/reinstall', function () use ($app) {
            /** @var \Doctrine\DBAL\Connection $conn */
            $conn = $app['db'];

            /** @var \Doctrine\DBAL\Schema\AbstractSchemaManager $sm */
            $sm = $conn->getSchemaManager();

            $schema = new \Doctrine\DBAL\Schema\Schema();

            $table = $schema->createTable('users');
            $table->addColumn("id", "integer", ["unsigned" => true]);
            $table->addColumn("username", "string", ["length" => 32]);
            $table->addColumn("age", "integer", ["unsigned" => true]);
            $table->setPrimaryKey(["id"]);
            $table->addUniqueIndex(["username"]);
            $schema->createSequence("users_seq");
            $sm->dropAndCreateTable($table);

            $table = $schema->createTable('messages');
            $table->addColumn("id", "integer", ["unsigned" => true]);
            $table->addColumn("author", "string", ["length" => 32]);
            $table->addColumn("parent", "integer", ["unsigned" => true]);
            $table->addColumn("message", "string", ["length" => 256]);
            $table->setPrimaryKey(["id"]);
            $sm->dropAndCreateTable($table);

            return 'DB installed';
        });


    }

}