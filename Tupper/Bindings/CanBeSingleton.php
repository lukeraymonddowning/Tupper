<?php


namespace Downing\Tupper\Bindings;


trait CanBeSingleton {

    protected $isSingleton = false, $singletonValue;

    public function provideSingleton($implementation): IoCProvidingInterface
    {
        return $this->provide($implementation, true);
    }

    public function isSingleton(): bool
    {
        return $this->isSingleton;
    }

    public function setSingletonIfEmpty($value): IoCProvidingInterface
    {
        if (empty($this->singletonValue))
            $this->singletonValue = $value;

        return $this;
    }

    public function getSingleton()
    {
        return $this->singletonValue;
    }

    public function makeSingleton($singleton = true)
    {
        $this->isSingleton = $singleton;
    }

}