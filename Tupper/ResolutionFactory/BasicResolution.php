<?php


namespace Downing\Tupper\ResolutionFactory;


class BasicResolution implements BindingResolutionInterface {

    protected $binding, $ioc;

    public function __construct($binding, $ioc)
    {
        $this->binding = $binding;
        $this->ioc = $ioc;
    }

    public function resolve()
    {
        return $this->binding;
    }
}