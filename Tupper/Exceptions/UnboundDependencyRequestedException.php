<?php

namespace Downing\Tupper\Exceptions;

use Exception;

class UnboundDependencyRequestedException extends Exception
{
    protected $message = 'You attempted to resolve a dependency from the IoC that has not been bound and cannot be resolved automatically.';
}
