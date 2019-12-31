<?php

namespace Downing\Tupper\Bindings;

interface IoCProvidingInterface
{
    public function provide($implementation, $isSingleton = false): self;

    public function provideSingleton($implementation): self;

    public function isSingleton(): bool;

    public function setSingletonIfEmpty($value): self;

    public function getSingleton();
}
