<?php

namespace Downing\Tupper\Exceptions;

use Downing\Tupper\Bindings\IoCBindingInterface;
use Exception;
use ReflectionClass;
use ReflectionException;

class ResolutionNotProvidedException extends Exception
{
    /**
     * ResolutionNotProvidedException constructor.
     *
     * @param IoCBindingInterface $binding
     *
     * @throws ReflectionException
     */
    public function __construct(IoCBindingInterface $binding)
    {
        $className = (new ReflectionClass($binding))->getName();
        $message = "You must give the binding for $className a value using the provide() method.";
        parent::__construct($message, 0, null);
    }
}
