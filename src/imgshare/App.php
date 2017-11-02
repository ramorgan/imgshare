<?php

namespace imgshare;

use Silex\Application as SilexApplication;
use Silex\Provider\DoctrineServiceProvider;
use Doctrine\DBAL\Connection;
use Igorw\Silex\ConfigServiceProvider;


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
        // Load the installation-specific configuration file. This should never be in Git.
        $app->register(new ConfigServiceProvider(__DIR__."/../../config/settings.json"));

        // Load environment-specific configuration.
        $app->register(new ConfigServiceProvider(__DIR__."/../../config/{$app['environment']}.json"));

        $app->register(
            new DoctrineServiceProvider(),
            [
                'db.options' => [
                    'driver'   => 'pdo_mysql',
                    'dbname'   => $app['database']['dbname'],
                    'host'     => $app['database']['host'],
                    'user'     => $app['database']['user'],
                    'password' => $app['database']['password'],
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