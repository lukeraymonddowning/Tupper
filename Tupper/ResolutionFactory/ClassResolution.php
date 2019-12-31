<?php

namespace Downing\Tupper\ResolutionFactory;

use ReflectionClass;

class ClassResolution extends DependencyResolution
{
    public function resolve()
    {
        $mirror = (new ReflectionClass($this->binding))->getConstructor();

        return new $this->binding(...$this->resolveDependencies(empty($mirror) ? [] : $mirror->getParameters()));
    }
}
