<?php

namespace Downing\Tupper\ResolutionFactory;


use Closure;
use Downing\Tupper\IoC;

class BindingResolutionFactory {

    private $binding, $ioc;

    public function __construct($binding, IoC $ioc)
    {
        $this->binding = $binding;
        $this->ioc = $ioc;
    }

    public function __invoke()
    {
        $resolvingClassName = $this->getResolutionClass();
        $resolvingClass = new $resolvingClassName($this->binding, $this->ioc);
        return $resolvingClass->resolve();
    }

    protected function getResolutionClass()
    {
        if ($this->binding instanceof Closure) {
            return ClosureResolution::class;
        } else if (is_string($this->binding) && class_exists($this->binding)) {
            return ClassResolution::class;
        }

        return BasicResolution::class;
    }

}