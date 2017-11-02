<?php

namespace imgshare;

use Silex\Application as SilexApplication;

class App extends SilexApplication
{
    public function __construct()
    {
        parent::__construct();


        $this->registerRoutes($this);
    }


    protected function registerRoutes(App $app)
    {
        $app->get(
            '/',
            function () use ($app) {
                return "Welcome to your new Silex Application!";
            }
        )->bind('homepage');
    }

}