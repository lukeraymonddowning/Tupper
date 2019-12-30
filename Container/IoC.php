<?php

namespace Downing\Container;

use Downing\Container\Bindings\IoCInitialBinder;
use Downing\Container\Bindings\IoCBindingInterface;
use Downing\Container\Bindings\BindingResolutionFactory;
use Downing\Container\Exceptions\UnboundDependencyRequestedException;

class IoC {

    protected $bindings = [];

    public function __construct()
    {
        $this->bindings[IoCBindingInterface::class] = new IoCInitialBinder();
    }

    public function __invoke($request)
    {
        return $this->request($request);
    }

    public function has($request)
    {
        return isset($this->bindings[$request]);
    }

    public function remove($request)
    {
        if ($this->has($request))
            unset($this->bindings[$request]);
    }

    public function whenGiven($request)
    {
        $binding = $this(IoCBindingInterface::class);
        $this->bindings[$request] = $binding;

        return $binding;
    }

    public function request($request)
    {
        if (isset($this->bindings[$request])) {
            return $this->resolveBinding($request);
        } else if (class_exists($request)) {
            return $this->resolveClass($request);
        }

        throw new UnboundDependencyRequestedException();
    }

    protected function resolveBinding($request) {
        $response = $this->bindings[$request];
        $binding = $response->getImplementationOrFail();

        return (new BindingResolutionFactory($binding, $this))();
    }

    protected function resolveClass($request) {
        return new $request;
    }

}