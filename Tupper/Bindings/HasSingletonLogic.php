<?php

namespace Downing\Tupper\Bindings;

trait HasSingletonLogic
{
    protected $isSingleton = false;
    protected $singletonValue;

    public function isSingleton(): bool
    {
        return $this->isSingleton;
    }

    public function setSingletonIfEmpty($value): IoCProvidingInterface
    {
        if (empty($this->singletonValue)) {
            $this->singletonValue = $value;
        }

        return $this;
    }

    public function getSingleton()
    {
        return $this->singletonValue;
    }

    public function setIsSingleton($singleton = true)
    {
        $this->isSingleton = $singleton;
    }
}
