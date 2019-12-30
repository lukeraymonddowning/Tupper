<?php


namespace Downing\Container\Bindings;


use Closure;
use Downing\Container\IoC;
use ReflectionClass;
use ReflectionFunction;
use ReflectionParameter;

class BindingResolutionFactory {

    private $binding, $ioc;

    public function __construct($binding, IoC $ioc)
    {
        $this->binding = $binding;
        $this->ioc = $ioc;
    }

    public function __invoke()
    {
        if ($this->binding instanceof Closure) {
            return $this->resolveClosure();
        } else if (is_string($this->binding) && class_exists($this->binding)) {
            return $this->resolveClass();
        }

        return $this->binding;
    }

    protected function resolveClosure()
    {
        $mirror = new ReflectionFunction($this->binding);

        return $mirror->invoke(...$this->resolveDependancies(empty($mirror) ? [] : $mirror->getParameters()));
    }

    protected function resolveClass()
    {
        $mirror = (new ReflectionClass($this->binding))->getConstructor();

        return new $this->binding(...$this->resolveDependancies(empty($mirror) ? [] : $mirror->getParameters()));
    }

    protected function resolveDependancies($dependancies)
    {
        return array_map(function (ReflectionParameter $parameter) {
            return $this->ioc->request($parameter->getType()->getName());
        }, $dependancies);
    }

}