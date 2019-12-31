<?php

namespace Downing\Tupper\Bindings;

use Downing\Tupper\Exceptions\ResolutionNotProvidedException;

class IoCBinding implements IoCBindingInterface, IoCProvidingInterface {

    use HasSingletonLogic;

    private $implementationProvided = false, $implementation;

    public function provide($implementation, $isSingleton = false): IoCProvidingInterface
    {
        $this->setImplementation($implementation)
             ->recordImplementationHasBeenProvided()
             ->setIsSingleton($isSingleton);

        return $this;
    }

    public function provideSingleton($implementation): IoCProvidingInterface
    {
        return $this->provide($implementation, true);
    }

    protected function setImplementation($implementation)
    {
        $this->implementation = $implementation;

        return $this;
    }

    protected function recordImplementationHasBeenProvided()
    {
        $this->implementationProvided = true;

        return $this;
    }

    public function getImplementationOrFail()
    {
        $this->failIfNoImplementationProvided();

        return $this->implementation;
    }

    protected function failIfNoImplementationProvided()
    {
        if (!$this->implementationProvided)
            throw new ResolutionNotProvidedException($this);
    }

}