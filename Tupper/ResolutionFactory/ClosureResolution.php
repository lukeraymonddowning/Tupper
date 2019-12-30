<?php


namespace Downing\Tupper\ResolutionFactory;


use ReflectionFunction;

class ClosureResolution extends DependencyResolution {

    public function resolve()
    {
        $mirror = new ReflectionFunction($this->binding);

        return $mirror->invoke(...$this->resolveDependencies(empty($mirror) ? [] : $mirror->getParameters()));
    }
}