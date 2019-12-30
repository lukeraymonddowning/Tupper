<?php

namespace Downing\Container\Bindings;

interface IoCBindingInterface {

    public function provide($implementation): self;

    public function getImplementationOrFail();

}