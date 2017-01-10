<?php

namespace App\Libraries\FormBuilder;

use App\Libraries\FormBuilder\Converter\JqueryValidation\Converter;

use App\Libraries\FormBuilder\FormBuilder;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class ServiceProvider extends IlluminateServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('formbuilder', function ($app) {

            $form = new FormBuilder($app['html'], $app['url'], $app['view'], $app['session.store']->getToken());

            return $form->setSessionStore($app['session.store']);
        });
    }


}