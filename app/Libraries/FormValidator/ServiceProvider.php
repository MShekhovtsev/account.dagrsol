<?php

namespace App\Libraries\FormValidator;

use App\Libraries\FormValidator\Converter\JqueryValidation\Converter;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class ServiceProvider extends IlluminateServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('formvalidator', function ($app) {
            $converter = new Converter();

            $form = new FormValidator($app['html'], $app['url'], $app['view'], $app['session.store']->getToken(), $converter);

            return $form->setSessionStore($app['session.store']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('formvalidator');
    }

}