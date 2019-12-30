<?php

namespace Downing\Tupper;

use Downing\Tupper\Bindings\IoCInitialBinder;
use Downing\Tupper\Bindings\IoCBindingInterface;
use Downing\Tupper\Bindings\IoCProvidingInterface;
use Downing\Tupper\ResolutionFactory\BindingResolutionFactory;
use Downing\Tupper\Exceptions\UnboundDependencyRequestedException;

class IoC {

    protected $bindings = [];

    public function __construct()
    {
        $this->bindings[IoCBindingInterface::class] = new IoCInitialBinder();
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
        $request = $this->transformGivenRequestToSafeOffset($request);

        $binding = $this(IoCBindingInterface::class);
        $this->bindings[$request] = $binding;

        return $binding;
    }

    public function __invoke($request)
    {
        return $this->request($request);
    }

    /**
     * Returns the requested implementation from the container if able.
     *
     * @param $request
     * @throws UnboundDependencyRequestedException
     * @return mixed
     */
    public function request($request)
    {
        $request = $this->transformGivenRequestToSafeOffset($request);

        if ($this->has($request)) {
            return $this->resolveBinding($request);
        } else if (class_exists($request)) {
            return new $request;
        }

        throw new UnboundDependencyRequestedException();
    }

    protected function resolveBinding($request)
    {
        $binding = $this->bindings[$request];

        $implementation = $binding->getImplementationOrFail();
        $resolution = (new BindingResolutionFactory($implementation, $this))();

        return $this->retrieveSingletonOrNewInstance($binding, $resolution);
    }

    protected function retrieveSingletonOrNewInstance($binding, $resolution)
    {
        return $binding instanceof IoCProvidingInterface && $binding->isSingleton() ?
            $binding->setSingletonIfEmpty($resolution)->getSingleton() :
            $resolution;
    }

    protected function transformGivenRequestToSafeOffset($request)
    {
        return is_array($request) ? serialize($request) : $request;
    }

}