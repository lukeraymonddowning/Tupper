<?php


namespace Downing\Tupper\ResolutionFactory;


interface BindingResolutionInterface {

    public function __construct($binding, $ioc);

    public function resolve();

}