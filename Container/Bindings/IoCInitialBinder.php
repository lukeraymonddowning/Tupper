<?php

namespace Downing\Container\Bindings;

class IoCInitialBinder implements IoCBindingInterface {

    public function getImplementationOrFail()
    {
        return IoCBinding::class;
    }
}