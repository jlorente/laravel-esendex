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

use Esendex\Authentication\IAuthentication;
use ReflectionClass;

/**
 * Class EsendexServiceProvider.
 *
 * @author Jose Lorente <jose.lorente.martin@gmail.com>
 */
class Esendex
{

    /**
     * The Config repository instance.
     *
     * @var IAuthentication
     */
    protected $config;

    /**
     * 
     * @param IAuthentication $config
     */
    public function __construct(IAuthentication $config)
    {
        $this->setConfig($config);
    }

    /**
     * 
     * @param IAuthentication $config
     * @return \static
     */
    public static function make(IAuthentication $config)
    {
        return new static($config);
    }

    /**
     * Returns the authentication object used in the api communication.
     *
     * @return IAuthentication
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Sets the authentication object used in the api communication.
     *
     * @param IAuthentication $config
     * @return $this
     */
    public function setConfig(IAuthentication $config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * Dynamically handle missing methods.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return \Jlorente\Appsflyer\Api\ApiInterface
     */
    public function __call($method, array $parameters)
    {
        return $this->getApiInstance($method, $parameters);
    }

    /**
     * Returns the Api class instance for the given method.
     *
     * @param  string  $method
     * @return mixed An essendex service
     * @throws \BadMethodCallException
     */
    protected function getApiInstance($method, array $parameters = null)
    {
        $class = "\\Esendex\\" . ucwords($method);

        if (class_exists($class) && !(new ReflectionClass($class))->isAbstract()) {
            $r = new ReflectionClass($class);
            if (!$parameters) {
                $params = $r->getConstructor()->getParameters();
                if (isset($params[0]) && $params[0]->getClass()) {
                    $argument = new ReflectionClass($params[0]->getClass()->name);
                    if ($argument->implementsInterface(IAuthentication::class)) {
                        $parameters = [$this->config];
                    }
                }
            }
            return $r->newInstanceArgs($parameters);
        }
        throw new \BadMethodCallException("Undefined method [{$method}] called.");
    }

}
