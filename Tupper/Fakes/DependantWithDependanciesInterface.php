<?php


namespace Downing\Tupper\Fakes;


interface DependantWithDependanciesInterface {

    public function __construct(EmptyInterface $dependency);

    public function getDependency(): EmptyInterface;

}