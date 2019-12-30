<?php


namespace Downing\Container\Bindings;


interface IoCProvidingInterface {

    public function provide($implementation): self;

}