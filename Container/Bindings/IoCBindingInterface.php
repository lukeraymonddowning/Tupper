<?php

namespace Container\Bindings;

interface IoCBindingInterface {

    public function provide($implementation): self;

    public function getImplementationOrFail();

}