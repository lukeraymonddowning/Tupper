<?php


namespace Downing\Tupper\Bindings;


interface IoCProvidingInterface {

    public function provide($implementation): self;

}