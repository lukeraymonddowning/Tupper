<?php

namespace Downing\Container\Bindings;

class IoCInitialBinder implements IoCBindingInterface {

    public function provide($implementation): IoCBindingInterface
    {
        return $this;
    }

    public function getImplementationOrFail()
    {
        return IoCBinding::class;
    }
}