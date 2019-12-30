<?php

namespace Downing\Tupper\Bindings;

use ReflectionException;
use Downing\Tupper\Exceptions\ResolutionNotProvidedException;

class IoCBinding implements IoCBindingInterface, IoCProvidingInterface {

    use CanBeSingleton;

    private $implementationProvided = false, $implementation;

    public function provide($implementation, $isSingleton = false): IoCProvidingInterface
    {
        $this->implementation = $implementation;
        $this->implementationProvided = true;
        $this->makeSingleton($isSingleton);

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