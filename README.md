# Tupper: the Dependency Injection container for PHP


[![Build Status](https://travis-ci.org/lukeraymonddowning/Tupper.svg?branch=master)](https://travis-ci.org/lukeraymonddowning/Tupper)
[![Coverage Status](https://coveralls.io/repos/github/lukeraymonddowning/Tupper/badge.svg?branch=master)](https://coveralls.io/github/lukeraymonddowning/Tupper?branch=master)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![StyleCI](https://github.styleci.io/repos/230770935/shield?branch=master)](https://github.styleci.io/repos/230770935/shield?branch=master)

A simple, no-nonsense declarative IoC Container written in PHP for Dependency Injection (DI).

Dependency Injection and Dependency Inversion are powerful concepts that improve code readability, maintainability and stability.

More often than not, you'll want a IoC container that allows you to manage these dependancies from one place. These containers are often convoluted and technically expensive, creating friction between you and your code. This library removes all of the fluff and leaves just the essentials, allowing you to quickly implement DI in your project.

# Installation
This package is available via composer:

`composer require downing/tupper`

# Basic Usage
To use the container, create an instance of it in your project:

```
<?php

$ioc = new Downing\Tupper\IoC();
```

Then, during your system registration, you'll want to register your dependencies. You can do so using the following syntax:

```
$ioc->whenGiven(YourAbstraction::class)
    ->provide(YourImplementation::class);
```

To resolve a dependency out of the container, you may do one the following:

```
// Using the request method
$implementation = $ioc->request(YourAbstraction::class);

// By invoking the class, which calls the request method behind the scenes
$implementation = $ioc(YourAbstraction::class);
```

You may bind almost any abstraction to any implementation. Here are some examples:

```
// Binding a string
$ioc->whenGiven("foo")
    ->provide("bar");

// Binding a closure, which will be executed when requested from the container
$ioc->whenGiven(YourAbstraction::class)
    ->provide(function() {
      return new YourImplementation();
    });

// You can even bind an array
$ioc->whenGiven([1, 2, 3])
    ->provide([3, 2, 1]);
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

Occasionally, you will want to bind a value into the container as a singleton. That is to say that every time you request 
an implementation from the same instance of a container, it should return a single reference rather than a new instance. You may do so with the following syntax:

```
$ioc->whenGiven(YourAbstraction::class)
    ->provideSingleton(YourImplementation::class)
```

When you resolve a dependency through the container, it will attempt to resolve any dependencies of that dependency through the container too. This allows for nested dependencies, which can be very powerful. 
*You do not need to bind a class if it does not rely on an implementation. The container will automatically resolve it for you upon request, even if it has its own dependencies.*

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

Bindings that provide a Closure are resolved in the same manner. This allows you to request dependencies in the closure parameters and have them automatically resolved for you to use. You may, of course, request a binding from the container inside the Closure too.

```
$ioc->whenGiven(YourAbstraction::class)
    ->provide(function(Dependency $dependency) {
      return new Decorator($dependency);
    });
```

This package has a full test suite written in PhpUnit, so please feel free to view the tests for advanced usage and to see what is possible.

# Get in Touch

If you have questions, suggestions or just want to chat, find me on Twitter [@LukeDowning19](https://twitter.com/LukeDowning19)
