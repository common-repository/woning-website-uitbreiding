<?php

namespace Tussendoor\PropertyWebsite\Api;

use WP_REST_Server;
use BadMethodCallException;
use InvalidArgumentExcpetion;

class RestRoute
{
    protected $method;
    protected $endpoint;
    protected $route;
    protected $callback;

    public function __call($name, $arguments)
    {
        if (strlen($name) > 3 && substr($name, 0, 3) === 'set') {
            return $this->setEndpointProperty($name, $arguments);
        }

        throw new BadMethodCallException("Method {$name} does not exist.");
    }

    public static function __callStatic($name, $arguments)
    {
        if (!in_array(strtoupper($name), self::getRequestMethods())) {
            throw new BadMethodCallException("Static method {$name} does not exist.");
        }

        list($endpoint, $route, $callback) = $arguments;

        return (new self)->setMethod('get')
            ->setEndpoint($endpoint)
            ->setRoute($route)
            ->setCallback($callback)
            ->register();
    }

    public function register()
    {
        return register_rest_route(
            $this->getEndpoint(),
            $this->getRoute(),
            ['methods' => $this->getMethod(), 'callback' => $this->getCallback()]
        );
    }

    protected function setEndpointProperty($name, $arguments)
    {
        $name = strtolower(substr($name, 3));
        if (!property_exists($this, $name)) {
            throw new BadMethodCallException("Setter method for property {$name} not found.");
        }

        $this->$name = reset($arguments);

        return $this;
    }

    protected function getEndpoint()
    {
        return $this->endpoint;
    }

    protected function getRoute()
    {
        return $this->route;
    }

    protected function getMethod()
    {
        $method = strtoupper($this->method);
        $supported = self::getRequestMethods();

        if (!in_array($method, $supported)) {
            throw new InvalidArgumentExcpetion("Unknown REST method \"{$method}\".");
        }

        return $method;
    }

    protected function getCallback()
    {
        if (!is_callable($this->callback)) {
            throw new InvalidArgumentExcpetion("The given callback is not callable.");
        }

        return $this->callback;
    }


    protected static function getRequestMethods()
    {
        return explode(', ', WP_REST_Server::ALLMETHODS);
    }
}
