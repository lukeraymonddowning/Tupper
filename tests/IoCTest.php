<?php

use Container\IoC;
use Container\Fakes\EmptyClass;
use PHPUnit\Framework\TestCase;
use Container\Bindings\IoCBinding;
use Container\Fakes\EmptyInterface;
use Container\Bindings\IoCBindingInterface;
use Container\Fakes\DependantWithDependancies;
use Container\Fakes\DependantWithDependanciesInterface;
use Container\Exceptions\ResolutionNotProvidedException;
use Container\Exceptions\UnboundDependencyRequestedException;

class IoCTest extends TestCase {

    /** @test */
    public function it_can_resolve_ioc_binding_interface_automatically()
    {
        $ioc = new IoC();
        $instance = $ioc->request(IoCBindingInterface::class);
        $this->assertInstanceOf(IoCBinding::class, $instance);
    }

    /** @test */
    public function it_can_be_given_bindings()
    {
        $ioc = new IoC();
        $result = $ioc->whenRequested("foo")
                      ->provide("bar");
        $this->assertInstanceOf(IoCBinding::class, $result);
    }

    /** @test */
    public function it_can_return_a_given_binding()
    {
        $ioc = new IoC();
        $ioc->whenRequested("foo")
            ->provide("bar");
        $this->assertEquals("bar", $ioc->request("foo"));
    }

    /** @test */
    public function it_returns_a_class_instance_if_a_given_binding_is_a_class()
    {
        $ioc = new IoC();
        $ioc->whenRequested("foo")
            ->provide(EmptyClass::class);

        $this->assertInstanceOf(EmptyClass::class, $ioc->request("foo"));
    }

    /** @test */
    public function a_bound_instance_can_be_resolved_using_the_ioc_invoke_method()
    {
        $ioc = new IoC();
        $ioc->whenRequested("foo")
            ->provide("bar");

        $this->assertEquals("bar", $ioc("foo"));
    }

    /** @test */
    public function it_can_resolve_the_dependancies_of_dependancies()
    {
        $ioc = new IoC();

        $ioc->whenRequested(DependantWithDependanciesInterface::class)
            ->provide(DependantWithDependancies::class);

        $ioc->whenRequested(EmptyInterface::class)
            ->provide(EmptyClass::class);

        $this->assertInstanceOf(EmptyClass::class, $ioc(DependantWithDependanciesInterface::class)->getDependency());

    }

    /** @test */
    public function it_throws_an_exception_if_a_binding_has_not_been_given_a_resolution()
    {
        $ioc = new IoC();
        $ioc->whenRequested(EmptyInterface::class);

        $this->expectException(ResolutionNotProvidedException::class);
        $ioc->request(EmptyInterface::class);

    }

    /** @test */
    public function it_can_be_given_a_closure_as_a_binding()
    {
        $ioc = new IoC();
        $ioc->whenRequested("foo")
            ->provide(function () {
                return "bar";
            });

        $this->assertEquals("bar", $ioc->request("foo"));
    }

    /** @test */
    public function it_can_be_given_a_closure_with_dependancies_as_a_binding()
    {
        $ioc = new IoC();

        $ioc->whenRequested(EmptyInterface::class)
            ->provide(EmptyClass::class);

        $ioc->whenRequested(DependantWithDependanciesInterface::class)
            ->provide(DependantWithDependancies::class);

        $ioc->whenRequested("foo")
            ->provide(function (EmptyInterface $dependency, DependantWithDependanciesInterface $anotherDependency) {
                return $dependency;
            });

        $this->assertInstanceOf(EmptyClass::class, $ioc->request("foo"));
    }

    /** @test */
    public function it_can_resolve_concrete_classes_that_have_not_been_bound_automatically() {
        $ioc = new IoC();
        $this->assertInstanceOf(EmptyClass::class, $ioc(EmptyClass::class));
    }

    /** @test */
    public function it_throws_an_exception_if_the_request_has_not_been_bound_and_cannot_be_resolved_automatically() {
        $ioc = new IoC();
        $this->expectException(UnboundDependencyRequestedException::class);
        $ioc("foo");
    }

    /** @test */
    public function it_can_be_queried_for_the_existence_of_a_binding() {
        $ioc = new IoC();
        $this->assertFalse($ioc->has("foo"));

        $ioc->whenRequested("foo")
            ->provide("bar");

        $this->assertTrue($ioc->has("foo"));
    }

    /** @test */
    public function a_binding_can_be_removed() {
        $ioc = new IoC();
        $ioc->whenRequested("foo")
            ->provide("bar");

        $this->assertTrue($ioc->has("foo"));
        $ioc->remove("foo");

        $this->assertFalse($ioc->has("foo"));
    }

    /** @test */
    public function requesting_the_removal_of_a_nonexistent_binding_fails_gracefully() {
        $ioc = new IoC();
        $ioc->remove("foo");

        $this->assertFalse($ioc->has("foo"));
    }

    /** @test */
    public function a_bound_closure_can_make_reference_to_the_container() {
        $ioc = new IoC();
        $ioc->whenRequested("foo")
            ->provide("bar");

        $ioc->whenRequested("pin")->provide(function() use ($ioc) {
            return $ioc->request("foo");
        });

        $this->assertEquals("bar", $ioc("pin"));
    }

}