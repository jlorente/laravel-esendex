<?php

/**
 * Part of the Esendex Laravel package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.
 *
 * @package    Esendex Laravel
 * @version    1.0.0
 * @author     Jose Lorente
 * @license    BSD License (3-clause)
 * @copyright  (c) 2018, Jose Lorente
 */

namespace Jlorente\Laravel\Esendex;

use Esendex\Authentication\LoginAuthentication;
use Illuminate\Support\ServiceProvider;
use Jlorente\Laravel\Esendex\Esendex;

/**
 * Class EsendexServiceProvider.
 * 
 * Register the provider in the app config file in order to use the Facade and 
 * the injection of the service container.
 *
 * You must add the configuration of your esendex account to the configuration 
 * file.
 * 
 * config/services.php
 * ```php
 * //other services
 * 'esendex' => [
 *     'reference' => 'YOUR ACCOUNT REFERENCE',
 *     'username' => 'YOUR ACCOUNT USERNAME',
 *     'password' => 'YOUR ACCOUNT PASSWORD',
 * ],
 * ```
 * @author Jose Lorente <jose.lorente.martin@gmail.com>
 */
class EsendexServiceProvider extends ServiceProvider
{

    /**
     * @inheritdoc
     */
    protected $defer = true;

    /**
     * {@inheritDoc}
     */
    public function register()
    {
        $this->app->singleton('esendex', function ($app) {
            $config = $app['config']->get('services.esendex');
            return new Esendex(new LoginAuthentication(
                    $config['reference'] ?? null
                    , $config['username'] ?? null
                    , $config['password'] ?? null
            ));
        });
        //$this->app->alias('esendex', Esendex::class);
    }

    /**
     * {@inheritDoc}
     */
    public function provides()
    {
        return [
            'esendex'
        ];
    }

}
