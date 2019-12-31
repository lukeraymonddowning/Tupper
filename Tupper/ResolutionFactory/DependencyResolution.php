<?php

namespace Downing\Tupper\ResolutionFactory;

use ReflectionParameter;

abstract class DependencyResolution implements BindingResolutionInterface
{
    protected $binding;
    protected $ioc;

    public function __construct($binding, $ioc)
    {
        $this->binding = $binding;
        $this->ioc = $ioc;
    }

    protected function resolveDependencies($dependancies)
    {
        return array_map(function (ReflectionParameter $parameter) {
            return $this->ioc->request($parameter->getType()->getName());
        }, $dependancies);
    }
}
