# Dependency Injection Container for PHP
A simple, no-nonsense IoC Container written in PHP for Dependency Injection (DI).

Dependency Injection and Dependency Inversion are powerful concepts that improve code readability, maintainability and stability.

More often than not, you'll want a IoC container that allows you to manage these dependancies from one place. These containers are often convoluted and technically expensive, creating friction between you and your code. This library removes all of the fluff and leaves just the essentials, allowing you to quickly implement DI in your project.

# Installation
This package is available via composer:

`composer require lukedowning/dependency-injection-container`

# Basic Usage
To use this the container, create an instance of it in your project:

```
<?php

$ioc = new Downing\Container\IoC();
```

Then, during your system registration, you'll want to register your dependencies. You can do so using the following syntax:

```
$ioc->whenGiven(YourAbstraction::class)
    ->provide(YourImplementation::class);
```

You may bind almost any abstraction to any implementation, and it will be resolved for you. For example:

```
$ioc->whenGiven("foo")
    ->provide("bar");
```

or...

```
$ioc->whenGiven(YourAbstraction::class)
    ->provide(function() {
      return new YourImplementation();
    });
```

or even...

```
$ioc->whenGiven([1, 2, 3])
    ->provide([3, 2, 1]);
```

To resolve a dependancy out of the container, you may do one the following:

```
// Using the request method
$implementation = $ioc->request(YourAbstraction::class);

// By invoking the class, which calls the request method behind the scenes
$implementation = $ioc(YourAbstraction::class);
```

You can check for the existence of a binding using the has method:

```
if ($ioc->has(YourAbstraction::class)) {
    // Do something...
}
```

You can also remove an existing binding using the remove method:

```
$ioc->remove(YourAbstraction::class);
```

# Advanced Usage

When you resolve a dependency through the container, it will attempt to resolve any dependencies of that dependency through the container too. This allows for nested dependencies, which can be very powerful.

```
class DependencyWithDependencies {
  
  public $dependency;
  
  public __construct(DependencyInterface $dependency) {
    $this->dependency = $dependency;
  }
  
}

$ioc->whenGiven(DependencyInterface::class)
    ->provide(DependencyImplementation::class);
    
$dependency = $ioc->request(DependencyWithDependencies::class);
$dependencyOfDependency = $dependency->dependency;
```

Additionally, bindings that provide a Closure are resolved in the same manner. This allows you to request dependencies in the closure parameters and have them automatically resolved for you to use. You may, of course, request a binding from the container inside the Closure too.

```
$ioc->whenGiven(YourAbstraction::class)
    ->provide(function(Dependency $dependency) {
      return new Decorator($dependency);
    });
```

This package has a full test suite written in PhpUnit, so please feel free to view the tests for advanced usage and to see what is possible.
