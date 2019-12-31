<?php

use Downing\Tupper\IoC;
use PHPUnit\Framework\TestCase;
use Downing\Tupper\Fakes\EmptyClass;
use Downing\Tupper\Bindings\IoCBinding;
use Downing\Tupper\Fakes\EmptyInterface;
use Downing\Tupper\Bindings\IoCBindingInterface;
use Downing\Tupper\Fakes\DependantWithDependancies;
use Downing\Tupper\Fakes\DependantWithDependanciesInterface;
use Downing\Tupper\Exceptions\ResolutionNotProvidedException;
use Downing\Tupper\Exceptions\UnboundDependencyRequestedException;

class IoCTest extends TestCase {

    /**
     * @var IoC
     */
    private $ioc;

    protected function setUp(): void
    {
        parent::setUp();
        $this->ioc = new IoC();
    }

    /** @test */
    public function it_can_resolve_ioc_binding_interface_automatically()
    {
        $instance = $this->ioc->request(IoCBindingInterface::class);
        $this->assertInstanceOf(IoCBinding::class, $instance);
    }

    /** @test */
    public function it_can_be_given_bindings()
    {
        $result = $this->ioc->whenGiven("foo")
                      ->provide("bar");
        $this->assertInstanceOf(IoCBinding::class, $result);
    }

    /** @test */
    public function it_can_return_a_given_binding()
    {
        $this->ioc->whenGiven("foo")
            ->provide("bar");
        $this->assertEquals("bar", $this->ioc->request("foo"));
    }

    /** @test */
    public function it_returns_a_class_instance_if_a_given_binding_is_a_class()
    {
        $this->ioc->whenGiven("foo")
            ->provide(EmptyClass::class);

        $this->assertInstanceOf(EmptyClass::class, $this->ioc->request("foo"));
    }

    /** @test */
    public function a_bound_instance_can_be_resolved_using_the_ioc_invoke_method()
    {
        $this->ioc->whenGiven("foo")
            ->provide("bar");

        $this->assertEquals("bar", $this->ioc->request("foo"));
    }

    /** @test */
    public function it_can_resolve_the_dependancies_of_dependancies()
    {
        $this->ioc->whenGiven(DependantWithDependanciesInterface::class)
            ->provide(DependantWithDependancies::class);

        $this->ioc->whenGiven(EmptyInterface::class)
            ->provide(EmptyClass::class);

        $this->assertInstanceOf(EmptyClass::class, $this->ioc->request(DependantWithDependanciesInterface::class)->getDependency());

    }

    /** @test */
    public function it_throws_an_exception_if_a_binding_has_not_been_given_a_resolution()
    {
        $this->ioc->whenGiven(EmptyInterface::class);

        $this->expectException(ResolutionNotProvidedException::class);
        $this->ioc->request(EmptyInterface::class);

    }

    /** @test */
    public function it_can_be_given_a_closure_as_a_binding()
    {
        $this->ioc->whenGiven("foo")
            ->provide(function () {
                return "bar";
            });

        $this->assertEquals("bar", $this->ioc->request("foo"));
    }

    /** @test */
    public function it_can_be_given_a_closure_with_dependancies_as_a_binding()
    {
        $this->ioc->whenGiven(EmptyInterface::class)
            ->provide(EmptyClass::class);

        $this->ioc->whenGiven(DependantWithDependanciesInterface::class)
            ->provide(DependantWithDependancies::class);

        $this->ioc->whenGiven("foo")
            ->provide(function (EmptyInterface $dependency, DependantWithDependanciesInterface $anotherDependency) {
                return $dependency;
            });

        $this->assertInstanceOf(EmptyClass::class, $this->ioc->request("foo"));
    }

    /** @test */
    public function it_can_resolve_concrete_classes_that_have_not_been_bound_automatically() {
        $this->assertInstanceOf(EmptyClass::class, $this->ioc->request(EmptyClass::class));
    }

    /** @test */
    public function it_throws_an_exception_if_the_request_has_not_been_bound_and_cannot_be_resolved_automatically() {
        $this->expectException(UnboundDependencyRequestedException::class);
        $this->ioc->request("foo");
    }

    /** @test */
    public function it_can_be_queried_for_the_existence_of_a_binding() {
        $this->assertFalse($this->ioc->has("foo"));

        $this->ioc->whenGiven("foo")
            ->provide("bar");

        $this->assertTrue($this->ioc->has("foo"));
    }

    /** @test */
    public function a_binding_can_be_removed() {
        $this->ioc->whenGiven("foo")
            ->provide("bar");

        $this->assertTrue($this->ioc->has("foo"));
        $this->ioc->remove("foo");

        $this->assertFalse($this->ioc->has("foo"));
    }

    /** @test */
    public function requesting_the_removal_of_a_nonexistent_binding_fails_gracefully() {
        $this->ioc->remove("foo");
        $this->assertFalse($this->ioc->has("foo"));
    }

    /** @test */
    public function a_bound_closure_can_make_reference_to_the_container() {
        $this->ioc->whenGiven("foo")
            ->provide("bar");

        $this->ioc->whenGiven("pin")->provide(function() {
            return $this->ioc->request("foo");
        });

        $this->assertEquals("bar", $this->ioc->request("pin"));
    }

    /** @test */
    public function it_can_bind_integers() {
        $this->ioc->whenGiven(1)
            ->provide(2);

        $this->assertEquals(2, $this->ioc->request(1));
    }

    /** @test */
    public function it_can_bind_floats() {
        $this->ioc->whenGiven(2.1)
            ->provide(3.2);

        $this->assertEquals(3.2, $this->ioc->request(2.1));
    }

    /** @test */
    public function it_can_bind_booleans() {
        $this->ioc->whenGiven(true)
            ->provide(false);

        $this->assertFalse($this->ioc->request(true));
    }

    /** @test */
    public function it_can_bind_arrays() {
        $this->ioc->whenGiven([1, 2, 3])
            ->provide([3, 2, 1]);

        $this->assertEquals([3, 2, 1], $this->ioc->request([1, 2, 3]));
    }

    /** @test */
    public function it_can_bind_null() {
        $this->ioc->whenGiven("foo")
            ->provide(null);

        $this->assertEquals(null, $this->ioc->request("foo"));
    }

    /** @test */
    public function it_can_accept_a_singleton() {
        $this->ioc->whenGiven(EmptyInterface::class)
            ->provideSingleton(EmptyClass::class);

        $this->assertSame($this->ioc->request(EmptyInterface::class), $this->ioc->request(EmptyInterface::class));
    }

    /** @test */
    public function when_it_is_not_a_singleton_it_returns_new_instances() {
        $this->ioc->whenGiven(EmptyInterface::class)
                  ->provide(EmptyClass::class);

        $this->assertNotSame($this->ioc->request(EmptyInterface::class), $this->ioc->request(EmptyInterface::class));
    }

}