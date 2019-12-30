<?php

namespace Downing\Tupper;

use Downing\Tupper\Bindings\IoCInitialBinder;
use Downing\Tupper\Bindings\IoCBindingInterface;
use Downing\Tupper\Bindings\BindingResolutionFactory;
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

    protected function transformGivenRequestToSafeOffset($request) {
        if (is_array($request)) {
            return serialize($request);
        }

        return $request;
    }

}