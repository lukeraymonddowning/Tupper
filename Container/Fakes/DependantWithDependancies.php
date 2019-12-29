<?php


namespace Container\Fakes;


class DependantWithDependancies implements DependantWithDependanciesInterface {

    public $dependency;

    public function __construct(EmptyInterface $dependency)
    {
        $this->dependency = $dependency;
    }

    public function getDependency(): EmptyInterface
    {
        return $this->dependency;
    }
}