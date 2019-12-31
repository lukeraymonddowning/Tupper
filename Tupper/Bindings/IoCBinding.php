<?php

namespace Downing\Tupper\Bindings;

use Downing\Tupper\Exceptions\ResolutionNotProvidedException;
use ReflectionException;

class IoCBinding implements IoCBindingInterface, IoCProvidingInterface
{
    use CanBeSingleton;

    private $implementationProvided = false;
    private $implementation;

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
     *
     * @return mixed
     */
    public function getImplementationOrFail()
    {
        if (!$this->implementationProvided) {
            throw new ResolutionNotProvidedException($this);
        }

        return $this->implementation;
    }
}
