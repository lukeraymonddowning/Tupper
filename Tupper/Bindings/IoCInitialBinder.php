<?php

namespace Downing\Tupper\Bindings;

class IoCInitialBinder implements IoCBindingInterface {

    public function getImplementationOrFail()
    {
        return IoCBinding::class;
    }
}