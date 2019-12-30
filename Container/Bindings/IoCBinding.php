<?php

namespace Downing\Container\Bindings;

use ReflectionException;
use Downing\Container\Exceptions\ResolutionNotProvidedException;

class IoCBinding implements IoCBindingInterface {

    private $implementationProvided = false;
    private $implementation;

    public function provide($implementation): IoCBindingInterface
    {
        $this->implementation = $implementation;
        $this->implementationProvided = true;
        return $this;
    }

    /**
     * @throws ResolutionNotProvidedException
     * @throws ReflectionException
     * @return mixed
     */
    public function getImplementationOrFail()
    {
        if (!$this->implementationProvided)
            throw new ResolutionNotProvidedException($this);

        return $this->implementation;
    }
}