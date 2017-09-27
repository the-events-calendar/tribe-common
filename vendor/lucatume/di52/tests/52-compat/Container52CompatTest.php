<?php

class Container52CompatTest extends PHPUnit_Framework_TestCase {

	public function boundVariables() {
		return array(
			array('bar'),
			array(23),
			array((object)array('prop' => 'value')),
			array(''),
		);
	}

	protected function setUp() {
		ClassEight::reset();
	}

	/**
	 * @test
	 * it should allow setting a var on the container
	 * @dataProvider boundVariables
	 */
	public function it_should_allow_setting_a_var_on_the_container($value) {
		$container = new tad_DI52_Container();

		$container->setVar('foo', $value);

		$this->assertEquals($value, $container->getVar('foo'));
	}

	/**
	 * @test
	 * it should allow setting a var on the container with ArrayAccess API
	 * @dataProvider boundVariables
	 */
	public function it_should_allow_setting_a_var_on_the_container_with_array_access_api($value) {
		$container = new tad_DI52_Container();

		$container['foo'] = $value;

		$this->assertEquals($value, $container['foo']);
	}

	/**
	 * @test
	 * it should return null when trying to get non existing var
	 */
	public function it_should_return_null_when_trying_to_get_non_existing_var() {
		$container = new tad_DI52_Container();

		$this->assertNull($container->getVar('foo'));
	}

	/**
	 * @test
	 * it should return throw when using ArrayAccess API to get non set var
	 */
	public function it_should_return_throw_when_using_array_access_api_to_get_non_set_var() {
		$container = new tad_DI52_Container();

		$this->setExpectedException('RuntimeException');

		$this->assertNull($container['foo']);
	}

	/**
	 * @test
	 * it should allow binding an implementation with no constructor to an interface
	 */
	public function it_should_allow_binding_an_implementation_with_no_constructor_to_an_interface() {
		$container = new tad_DI52_Container();

		$container->bind('One', 'ClassOne');

		$this->assertInstanceOf('ClassOne', $container->make('One'));
	}

	/**
	 * @test
	 * it should return a different instance of a bound interface implementation on each build
	 */
	public function it_should_return_a_different_instance_of_a_bound_interface_implementation_on_each_build() {
		$container = new tad_DI52_Container();

		$container->bind('One', 'ClassOne');

		$this->assertNotSame($container->make('One'), $container->make('One'));
	}

	/**
	 * @test
	 * it should allow binding an implementation with constructor to an interface
	 */
	public function it_should_allow_binding_an_implementation_with_constructor_to_an_interface() {
		$container = new tad_DI52_Container();

		$container->bind('One', 'ClassOneOne');

		$this->assertInstanceOf('ClassOneOne', $container->make('One'));
	}

	/**
	 * @test
	 * it should allow binding an implementation with constructor arguments
	 */
	public function it_should_allow_binding_an_implementation_with_constructor_arguments() {
		$container = new tad_DI52_Container();

		$container->bind('One', 'ClassOneTwo');

		$this->assertInstanceOf('ClassOneTwo', $container->make('One'));
	}

	/**
	 * @test
	 * it should allow binding an implementation to a string slug
	 */
	public function it_should_allow_binding_an_implementation_to_a_string_slug() {
		$container = new tad_DI52_Container();

		$container->bind('foo', 'ClassOneTwo');

		$this->assertInstanceOf('ClassOneTwo', $container->make('foo'));
	}

	/**
	 * @test
	 * it should allow binding an implementation to a class
	 */
	public function it_should_allow_binding_an_implementation_to_a_class() {
		$container = new tad_DI52_Container();

		$container->bind('ClassOne', 'ClassOneOne');

		$this->assertInstanceOf('ClassOneOne', $container->make('ClassOne'));
	}

	/**
	 * @test
	 * it should resolve unbound class with no constructor
	 */
	public function it_should_resolve_unbound_class_with_no_constructor() {
		$container = new tad_DI52_Container();

		$this->assertInstanceOf('ClassOne', $container->make('ClassOne'));
	}

	/**
	 * @test
	 * it should resolve an unbound class with a defaulted scalar dependency
	 */
	public function it_should_resolve_an_unbound_class_with_a_defaulted_scalar_dependency() {
		$container = new tad_DI52_Container();

		$this->assertInstanceOf('ClassOneTwo', $container->make('ClassOneTwo'));
	}

	/**
	 * @test
	 * it should throw if trying to resolve class with unbound interface dependency
	 */
	public function it_should_throw_if_trying_to_resolve_class_with_unbound_interface_dependency() {
		$this->setExpectedException('RuntimeException');

		$container = new tad_DI52_Container();

		$container->make('ClassTwo');
	}

	/**
	 * @test
	 * it should resolve an unbound class with an interface dependency
	 */
	public function it_should_resolve_an_unbound_class_with_an_interface_dependency() {
		$container = new tad_DI52_Container();

		$container->bind('One', 'ClassOne');

		$this->assertInstanceOf('ClassTwo', $container->make('ClassTwo'));
	}

	/**
	 * @test
	 * it should resolve an unbound class with a class dependency
	 */
	public function it_should_resolve_an_unbound_class_with_a_class_dependency() {
		$container = new tad_DI52_Container();

		$this->assertInstanceOf('ClassTwoOne', $container->make('ClassTwoOne'));
	}

	/**
	 * @test
	 * it should resolve an unbound class with plus one interface dependencies
	 */
	public function it_should_resolve_an_unbound_class_with_plus_one_interface_dependencies() {
		$container = new tad_DI52_Container();

		$container->bind('One', 'ClassOne');
		$container->bind('Two', 'ClassTwo');

		$this->assertInstanceOf('ClassThree', $container->make('ClassThree'));
	}

	/**
	 * @test
	 * it should resolve an unbound class with plus one class dependencies
	 */
	public function it_should_resolve_an_unbound_class_with_plus_one_class_dependencies() {
		$container = new tad_DI52_Container();

		$container->bind('One', 'ClassOne');

		$this->assertInstanceOf('ClassThreeOne', $container->make('ClassThreeone'));
	}

	/**
	 * @test
	 * it should resolve an unbound class with plus one interface and class dependencies
	 */
	public function it_should_resolve_an_unbound_class_with_plus_one_interface_and_class_dependencies() {
		$container = new tad_DI52_Container();

		$this->assertInstanceOf('ClassThreeTwo', $container->make('ClassThreeTwo'));
	}

	/**
	 * @test
	 * it should throw if trying to resolve class with non type-hinted dependency without default
	 */
	public function it_should_throw_if_trying_to_resolve_class_with_non_type_hinted_dependency_without_default() {
		$container = new tad_DI52_Container();

		$this->setExpectedException('RuntimeException');

		$container->make('ClassFour');
	}

	/**
	 * @test
	 * it should allow binding a class to an interface as a singleton
	 */
	public function it_should_allow_binding_a_class_to_an_interface_as_a_singleton() {
		$container = new tad_DI52_Container();

		$container->singleton('One', 'ClassOne');

		$this->assertInstanceOf('ClassOne', $container->make('One'));
		$this->assertSame($container->make('One'), $container->make('One'));
	}

	/**
	 * @test
	 * it should allow binding a class to a class as a singleton
	 */
	public function it_should_allow_binding_a_class_to_a_class_as_a_singleton() {
		$container = new tad_DI52_Container();

		$container->singleton('ClassOne', 'ClassOneOne');

		$this->assertInstanceOf('ClassOneOne', $container->make('ClassOne'));
		$this->assertSame($container->make('ClassOne'), $container->make('ClassOne'));
	}

	/**
	 * @test
	 * it should allow binding a class to a string slug as a singleton
	 */
	public function it_should_allow_binding_a_class_to_a_string_slug_as_a_singleton() {
		$container = new tad_DI52_Container();

		$container->singleton('one.foo', 'ClassOne');

		$this->assertInstanceOf('ClassOne', $container->make('one.foo'));
		$this->assertSame($container->make('one.foo'), $container->make('one.foo'));
	}

	public function implementationKeysAndValues() {
		return array(
			array('One', 'ClassOne'),
			array('ClassOne', 'ClassOneOne'),
		);
	}

	/**
	 * @test
	 * it should bind an implementation as singleton when using ArrayAccess API
	 * @dataProvider implementationKeysAndValues
	 */
	public function it_should_bind_an_implementation_as_singleton_when_using_array_access_api($key, $value) {
		$container = new tad_DI52_Container();

		$container[$key] = $value;

		$this->assertInstanceOf($value, $container[$key]);
		$this->assertSame($container[$key], $container[$key]);
	}

	/**
	 * @test
	 * it should allow binding a decorator chain to an interface
	 */
	public function it_should_allow_binding_a_decorator_chain_to_an_interface() {
		$container = new tad_DI52_Container();

		$container->bindDecorators('Four', array('FourDecoratorOne', 'FourDecoratorTwo', 'FourDecoratorThree', 'FourBase'));

		$this->assertInstanceOf('Four', $container->make('Four'));
		$this->assertNotSame($container->make('Four'), $container->make('Four'));
	}

	/**
	 * @test
	 * it should allow specifying after build methods to call on a decorator chain base
	 */
	public function it_should_allow_specifying_after_build_methods_to_call_on_a_decorator_chain_base() {
		$container = new tad_DI52_Container();

		$container->bindDecorators('Four', array('FourDecoratorOne', 'FourDecoratorTwo', 'FourDecoratorThree', 'FourBase'),
			array('methodOne', 'methodTwo'));

		$this->assertInstanceOf('Four', $container->make('Four'));
		$this->assertNotSame($container->make('Four'), $container->make('Four'));

		global $one, $two;

		$this->assertEquals('FourBase', $one);
		$this->assertEquals('FourBase', $two);
	}

	/**
	 * @test
	 * it should allow binding a chain as a singleton to an interface
	 */
	public function it_should_allow_binding_a_chain_as_a_singleton_to_an_interface() {
		$container = new tad_DI52_Container();

		$container->singletonDecorators('Four',
			array('FourDecoratorOne', 'FourDecoratorTwo', 'FourDecoratorThree', 'FourBase'));

		$this->assertInstanceOf('Four', $container->make('Four'));
		$this->assertSame($container->make('Four'), $container->make('Four'));
	}

	/**
	 * @test
	 * it should allow binding a complex chain to an interface
	 */
	public function it_should_allow_binding_a_complex_chain_to_an_interface() {
		$container = new tad_DI52_Container();

		$container->bind('Four', 'FourTwo');
		$container->bind('One', 'ClassOne');
		$container->bind('Two', 'ClassTwo');

		$container->bindDecorators('Five', array('FiveDecoratorOne', 'FiveDecoratorTwo', 'FiveDecoratorThree', 'FiveBase'));

		$this->assertInstanceOf('Five', $container->make('Five'));
		$this->assertNotSame($container->make('Five'), $container->make('Five'));
	}

	/**
	 * @test
	 * it should allow binding a complex chain as a singleton to an interface
	 */
	public function it_should_allow_binding_a_complex_chain_as_a_singleton_to_an_interface() {
		$container = new tad_DI52_Container();

		$container->bind('Four', 'FourTwo');
		$container->bind('One', 'ClassOne');
		$container->bind('Two', 'ClassTwo');

		$container->singletonDecorators('Five',
			array('FiveDecoratorOne', 'FiveDecoratorTwo', 'FiveDecoratorThree', 'FiveBase'));

		$this->assertInstanceOf('Five', $container->make('Five'));
		$this->assertSame($container->make('Five'), $container->make('Five'));
	}

	/**
	 * @test
	 * it should allow tagging and resolving tagged classes
	 */
	public function it_should_allow_tagging_and_resolving_tagged_classes() {
		$container = new tad_DI52_Container();

		$container->tag(array('ClassOne', 'ClassOneOne', 'ClassOneTwo'), 'foo');
		$made = $container->tagged('foo');

		$this->assertInstanceOf('ClassOne', $made[0]);
		$this->assertInstanceOf('ClassOneOne', $made[1]);
		$this->assertInstanceOf('ClassOneTwo', $made[2]);
	}

	/**
	 * @test
	 * it should throw if trying to resolve non existing tag
	 */
	public function it_should_throw_if_trying_to_resolve_non_existing_tag() {
		$container = new tad_DI52_Container();

		$this->setExpectedException('RuntimeException');

		$container->tagged('foo');
	}

	/**
	 * @test
	 * it should allow tagging mixed values
	 */
	public function it_should_allow_tagging_mixed_values() {
		$container = new tad_DI52_Container();

		$container->tag(array('ClassOne', new ClassOneOne(), 'ClassOneTwo'), 'foo');
		$made = $container->tagged('foo');

		$this->assertInstanceOf('ClassOne', $made[0]);
		$this->assertInstanceOf('ClassOneOne', $made[1]);
		$this->assertInstanceOf('ClassOneTwo', $made[2]);
	}

	/**
	 * @test
	 * it should call register method on on deferred service providers when registering
	 */
	public function it_should_call_register_method_on_on_deferred_service_providers_when_registering() {
		$container = new tad_DI52_Container();

		$container->register('ProviderOne');

		$this->assertTrue($container->isBound('foo'));
	}

	/**
	 * @test
	 * it should not call register method on deferred provider on registration
	 */
	public function it_should_not_call_register_method_on_deferred_provider_on_registration() {
		DeferredProviderTwo::reset();

		$container = new tad_DI52_Container();

		$container->register('DeferredProviderTwo');

		$this->assertEmpty(DeferredProviderTwo::wasRegistered());
	}

	/**
	 * @test
	 * it should throw if deferred provider is not providing anything
	 */
	public function it_should_throw_if_deferred_provider_is_not_providing_anything() {
		$container = new tad_DI52_Container();

		$this->setExpectedException('RuntimeException');

		$container->register('DeferredProviderOne');
	}

	/**
	 * @test
	 * it should register deferred provider when trying to resolve provided class
	 */
	public function it_should_register_deferred_provider_when_trying_to_resolve_provided_class() {
		DeferredProviderTwo::reset();

		$container = new tad_DI52_Container();

		$container->register('DeferredProviderTwo');

		$this->assertFalse(DeferredProviderTwo::wasRegistered());

		$container->make('One');

		$this->assertTrue(DeferredProviderTwo::wasRegistered());
	}

	/**
	 * @test
	 * it should call boot method on providers when booting the container
	 */
	public function it_should_call_boot_method_on_providers_when_booting_the_container() {
		$container = new tad_DI52_Container();

		$container->register('ProviderThree');

		$this->assertFalse($container->isBound('One'));

		$container->boot();

		$this->assertTrue($container->isBound('One'));
	}

	/**
	 * @test
	 * it should call after build methods on implementations
	 */
	public function it_should_call_after_build_methods_on_implementations() {
		$container = new tad_DI52_Container();

		$container->bind('One', 'ClassOneThree', array('methodOne', 'methodTwo'));

		$one = $container->make('One');
		$this->assertTrue($one->oneCalled);
		$this->assertTrue($one->twoCalled);
	}

	/**
	 * @test
	 * it should bind an object implementation as a singleton even when using bind
	 */
	public function it_should_bind_an_object_implementation_as_a_singleton_even_when_using_bind() {
		$container = new tad_DI52_Container();

		$object = new stdClass();

		$container->bind('foo', $object);

		$this->assertInstanceOf('stdClass', $container->make('foo'));
		$this->assertSame($container->make('foo'), $container->make('foo'));
	}

	/**
	 * @test
	 * it should allow binding an object to an interface as a singleton and resolving it using bind
	 */
	public function it_should_allow_binding_an_object_to_an_interface_as_a_singleton_and_resolving_it_using_bind() {
		$container = new tad_DI52_Container();

		$object = new ClassOne();

		$container->bind('One', $object);

		$this->assertInstanceOf('ClassOne', $container->make('One'));
		$this->assertInstanceOf('ClassOne', $container['One']);
		$this->assertSame($container->make('One'), $container['One']);
	}

	/**
	 * @test
	 * it should allow binding an object to an interface as a singleton and resolving it
	 */
	public function it_should_allow_binding_an_object_to_an_interface_as_a_singleton_and_resolving_it() {
		$container = new tad_DI52_Container();

		$object = new ClassOne();

		$container->singleton('One', $object);

		$this->assertInstanceOf('ClassOne', $container->make('One'));
		$this->assertInstanceOf('ClassOne', $container['One']);
		$this->assertSame($container->make('One'), $container['One']);
	}

	/**
	 * @test
	 * it should support contextual binding of interfaces
	 */
	public function it_should_support_contextual_binding_of_interfaces() {
		$container = new tad_DI52_Container();

		$container->bind('One', 'ClassOne');

		$container->when('ClassSix')
			->needs('One')
			->give('ClassOneOne');

		$container->when('ClassSeven')
			->needs('One')
			->give('ClassOneTwo');

		$this->assertInstanceOf('ClassOne', $container->make('One'));
		$this->assertInstanceOf('ClassOneOne', $container->make('ClassSix')->getOne());
		$this->assertInstanceOf('ClassOneTwo', $container->make('ClassSeven')->getOne());
	}

	/**
	 * @test
	 * it should support contextual binding of classes
	 */
	public function it_should_support_contextual_binding_of_classes() {
		$container = new tad_DI52_Container();

		$container->when('ClassSixOne')
			->needs('ClassOne')
			->give('ExtendingClassOneOne');

		$container->when('ClassSevenOne')
			->needs('ClassOne')
			->give('ExtendingClassOneTwo');

		$this->assertInstanceOf('ClassOne', $container->make('ClassOne'));
		$this->assertInstanceOf('ExtendingClassOneOne', $container->make('ClassSixOne')->getOne());
		$this->assertInstanceOf('ExtendingClassOneTwo', $container->make('ClassSevenOne')->getOne());
	}

	/**
	 * @test
	 * it should throw when trying to make non existing class
	 */
	public function it_should_throw_when_trying_to_make_non_existing_class() {
		$container = new tad_DI52_Container();

		$this->setExpectedException('RuntimeException');

		$container->make('SomeNonExistingClass');
	}

	/**
	 * @test
	 * it should replace a binding when re-binding
	 */
	public function it_should_replace_a_binding_when_re_binding() {
		$container = new tad_DI52_Container();

		$container->bind('One', 'ClassOne');

		$this->assertInstanceOf('ClassOne', $container->make('One'));

		$container->bind('One', 'ClassOneOne');

		$this->assertInstanceOf('ClassOneOne', $container->make('One'));
	}

	/**
	 * @test
	 * it should replace a singleton bind when re-binding a singleton binding
	 */
	public function it_should_replace_a_singleton_bind_when_re_binding_a_singleton_binding() {
		$container = new tad_DI52_Container();

		$container->singleton('One', 'ClassOne');

		$this->assertInstanceOf('ClassOne', $container->make('One'));

		$container->bind('One', 'ClassOneOne');

		$this->assertInstanceOf('ClassOneOne', $container->make('One'));
		$this->assertNotSame($container->make('One'), $container->make('One'));
	}

	/**
	 * @test
	 * it should replace bind with singleton if re-binding as singleton
	 */
	public function it_should_replace_bind_with_singleton_if_re_binding_as_singleton() {
		$container = new tad_DI52_Container();

		$container->singleton('One', 'ClassOne');

		$this->assertInstanceOf('ClassOne', $container->make('One'));
		$this->assertSame($container->make('One'), $container->make('One'));

		$container->singleton('One', 'ClassOneOne');

		$this->assertInstanceOf('ClassOneOne', $container->make('One'));
		$this->assertSame($container->make('One'), $container->make('One'));
	}

	/**
	 * @test
	 * it should replace singleton with simple bind if re-binding as non singleton
	 */
	public function it_should_replace_singleton_with_simple_bind_if_re_binding_as_non_singleton() {
		$container = new tad_DI52_Container();

		$container->singleton('One', 'ClassOne');

		$this->assertInstanceOf('ClassOne', $container->make('One'));
		$this->assertSame($container->make('One'), $container->make('One'));

		$container->bind('One', 'ClassOneOne');

		$this->assertInstanceOf('ClassOneOne', $container->make('One'));
		$this->assertNotSame($container->make('One'), $container->make('One'));
	}

	/**
	 * @test
	 * it should allow tagging non concrete implementations
	 */
	public function it_should_allow_tagging_non_concrete_implementations() {
		$container = new tad_DI52_Container();

		$container->bind('foo', 'ClassOne');
		$container->bind('One', 'ClassOne');

		$container->tag(array('foo', 'One'), 'bar');

		$resolved = $container->tagged('bar');

		$this->assertCount(2, $resolved);
		$this->assertInstanceOf('ClassOne', $resolved[0]);
		$this->assertInstanceOf('ClassOne', $resolved[1]);
		$this->assertNotSame($resolved[0], $resolved[1]);
	}

	/**
	 * @test
	 * it should allow lazy building an interface binding
	 */
	public function it_should_allow_lazy_building_an_interface_binding() {
		$container = new tad_DI52_Container();

		$container->bind('Eight', 'ClassEight');
		$f = $container->callback('Eight', 'methodOne');

		$f();

		$this->assertEquals(array('methodOne'), ClassEight::$called);
		$this->assertNotSame($container->make('Eight'), $container->make('Eight'));
	}

	/**
	 * @test
	 * it should allow lazy building an class binding
	 */
	public function it_should_allow_lazy_building_an_class_binding() {
		$container = new tad_DI52_Container();

		$container->bind('ClassEight', 'ClassEightExtension');
		$f = $container->callback('ClassEight', 'methodOne');

		$f();

		$this->assertEquals(array('methodOne'), ClassEightExtension::$called);
		$this->assertNotSame($container->make('ClassEight'), $container->make('ClassEight'));
	}

	/**
	 * @test
	 * it should allow lazy building an slug binding
	 */
	public function it_should_allow_lazy_building_an_slug_binding() {
		$container = new tad_DI52_Container();

		$container->bind('foo', 'ClassEight');
		$f = $container->callback('foo', 'methodOne');

		$f();

		$this->assertEquals(array('methodOne'), ClassEight::$called);
		$this->assertNotSame($container->make('foo'), $container->make('foo'));
	}

	/**
	 * @test
	 * it should allow lazy binding a singleton interface binding
	 */
	public function it_should_allow_lazy_binding_a_singleton_interface_binding() {
		$container = new tad_DI52_Container();

		$container->singleton('Eight', 'ClassEight');
		$f = $container->callback('Eight', 'methodOne');

		$f();

		$this->assertSame($container->make('Eight'), $container->make('Eight'));
		$this->assertEquals(array('methodOne'), ClassEight::$called);
	}

	/**
	 * @test
	 * it should allow lazy binding a singleton class binding
	 */
	public function it_should_allow_lazy_binding_a_singleton_class_binding() {
		$container = new tad_DI52_Container();

		$container->singleton('ClassEight', 'ClassEightExtension');
		$f = $container->callback('ClassEight', 'methodOne');

		$f();

		$this->assertSame($container->make('ClassEight'), $container->make('ClassEight'));
		$this->assertEquals(array('methodOne'), ClassEight::$called);
	}

	/**
	 * @test
	 * it should allow lazy binding a singleton slug binding
	 */
	public function it_should_allow_lazy_binding_a_singleton_slug_binding() {
		$container = new tad_DI52_Container();

		$container->singleton('foo', 'ClassEight');
		$f = $container->callback('foo', 'methodOne');

		$f();

		$this->assertSame($container->make('foo'), $container->make('foo'));
		$this->assertEquals(array('methodOne'), ClassEight::$called);
	}

	/**
	 * @test
	 * it should pass the calling arguments to the lazy made instance
	 */
	public function it_should_pass_the_calling_arguments_to_the_lazy_made_instance() {
		$container = new tad_DI52_Container();

		$container->bind('foo', 'ClassEight');
		$f = $container->callback('foo', 'methodFour');

		$f('foo', 23);
		$this->assertEquals(array('foo', 23), ClassEight::$calledWith);
	}

	/**
	 * @test
	 * it should allow lazy making a decorator binding
	 */
	public function it_should_allow_lazy_making_a_decorator_binding() {
		$container = new tad_DI52_Container();

		$container->bindDecorators('Four', array('FourDecoratorOne', 'FourDecoratorTwo', 'FourDecoratorThree', 'FourBase'));

		$f = $container->callback('Four', 'methodOne');
		$this->assertEquals(26, $f(3));
	}

	/**
	 * @test
	 * it should allow lazy making a decorator singleton binding
	 */
	public function it_should_allow_lazy_making_a_decorator_singleton_binding() {
		$container = new tad_DI52_Container();

		$container->singletonDecorators('Four',
			array('FourDecoratorOne', 'FourDecoratorTwo', 'FourDecoratorThree', 'FourBase'));

		$f = $container->callback('Four', 'methodOne');
		$this->assertEquals(26, $f(3));
		$this->assertSame($container->make('Four'), $container->make('Four'));
	}

	/**
	 * @test
	 * it should allow lazy making an unbound class
	 */
	public function it_should_allow_lazy_making_an_unbound_class() {
		$container = new tad_DI52_Container();

		$f = $container->callback('FourBase', 'methodThree');

		$this->assertEquals(26, $f(3));
	}

	/**
	 * @test
	 * it should throw if trying to lazy make a non string method
	 */
	public function it_should_throw_if_trying_to_lazy_make_a_non_string_method() {
		$container = new tad_DI52_Container();

		$this->setExpectedException('RuntimeException');

		$container->callback('foo', 23);
	}

	/**
	 * @test
	 * it should apply contextual binding to unbound classes
	 */
	public function it_should_apply_contextual_binding_to_unbound_classes() {
		$container = new tad_DI52_Container();

		$container->bind('One', 'ClassOne');
		$container->bind('Two', 'ClassTwo');
		$container->when('ClassTwoOne')->needs('ClassOne')->give('ExtendingClassOneOne');

		$resolved = $container->make('ClassTwoOne');
		$this->assertInstanceOf('ExtendingClassOneOne', $resolved->getOne());
	}

	/**
	 * @test
	 * it should allow lazy making contextually bound interfaces
	 */
	public function it_should_allow_lazy_making_contextually_bound_interfaces() {
		$container = new tad_DI52_Container();

		$container->bind('One', 'ClassOne');
		$container->bind('Two', 'ClassTwo');
		$container->when('ClassTwoOne')->needs('ClassOne')->give('ExtendingClassOneOne');

		$f = $container->callback('ClassTwoOne', 'getOne');

		$this->assertInstanceOf('ExtendingClassOneOne', $f());
	}

	/**
	 * @test
	 * it should return same instance when lazy making contextually bound singleton
	 */
	public function it_should_return_same_instance_when_lazy_making_contextually_bound_singleton() {
		$container = new tad_DI52_Container();

		$container->bind('One', 'ClassOne');
		$container->bind('Two', 'ClassTwo');
		$container->singleton('two.one', 'ClassTwoOne');
		$container->when('two.one')->needs('ClassOne')->give('ExtendingClassOneOne');

		$f = $container->callback('two.one', 'getOne');

		$this->assertInstanceOf('ExtendingClassOneOne', $f());
		$this->assertSame($f(), $f());
	}

	/**
	 * @test
	 * it should not build lazy made object immediately
	 */
	public function it_should_not_build_lazy_made_object_immediately() {
		$container = new tad_DI52_Container();

		ClassNine::reset();
		$f = $container->callback('ClassNine', 'methodOne');

		global $nine;
		$this->assertEmpty($nine);

		$f();

		$this->assertEquals('called', $nine);
	}

	/**
	 * @test
	 * it should mark a deferred implementation as bound before registering the service provider
	 */
	public function it_should_mark_a_deferred_implementation_as_bound_before_registering_the_service_provider() {
		DeferredProviderTwo::reset();

		$container = new tad_DI52_Container();

		$container->register('DeferredProviderTwo');

		$this->assertTrue($container->isBound('One'));
	}

	/**
	 * @test
	 * it should allow getting a callback to build an object
	 */
	public function it_should_allow_getting_a_callback_to_build_an_object() {
		ClassTen::reset();

		$container = new tad_DI52_Container();

		$f = $container->instance('ClassTen', array('foo', 'baz', 'bar'));

		$this->assertEquals(0, ClassTen::$builtTimes);

		$instance1 = $f();

		$this->assertEquals(1, ClassTen::$builtTimes);
		$this->assertEquals('foo', $instance1->getVarOne());
		$this->assertEquals('baz', $instance1->getVarTwo());
		$this->assertEquals('bar', $instance1->getVarThree());

		$instance2 = $f();

		$this->assertEquals(2, ClassTen::$builtTimes);
		$this->assertEquals('foo', $instance2->getVarOne());
		$this->assertEquals('baz', $instance2->getVarTwo());
		$this->assertEquals('bar', $instance2->getVarThree());

		$instance3 = $f();

		$this->assertEquals(3, ClassTen::$builtTimes);
		$this->assertEquals('foo', $instance3->getVarOne());
		$this->assertEquals('baz', $instance3->getVarTwo());
		$this->assertEquals('bar', $instance3->getVarThree());
	}

	/**
	 * @test
	 * it should allow getting a callback to build an object with scalar and object dependencies
	 */
	public function it_should_allow_getting_a_callback_to_build_an_object_with_scalar_and_object_dependencies() {
		ClassEleven::reset();

		$container = new tad_DI52_Container();

		$container->bind('One', 'ClassOneTwo');
		$container->bind('Two', 'ClassTwo');

		$f = $container->instance('ClassEleven', array('ClassOne', 'Two', 'bar'));

		$this->assertEquals(0, ClassEleven::$builtTimes);

		$instance1 = $f();

		$this->assertEquals(1, ClassEleven::$builtTimes);
		$this->assertInstanceOf('ClassOne', $instance1->getVarOne());
		$this->assertInstanceOf('ClassTwo', $instance1->getVarTwo());
		$this->assertInstanceOf('ClassOneTwo', $instance1->getVarTwo()->getOne());
		$this->assertEquals('bar', $instance1->getVarThree());

		$instance2 = $f();

		$this->assertEquals(2, ClassEleven::$builtTimes);
		$this->assertInstanceOf('ClassOne', $instance2->getVarOne());
		$this->assertInstanceOf('ClassTwo', $instance2->getVarTwo());
		$this->assertInstanceOf('ClassOneTwo', $instance2->getVarTwo()->getOne());
		$this->assertEquals('bar', $instance2->getVarThree());

		$instance3 = $f();

		$this->assertEquals(3, ClassEleven::$builtTimes);
		$this->assertInstanceOf('ClassOne', $instance3->getVarOne());
		$this->assertInstanceOf('ClassTwo', $instance3->getVarTwo());
		$this->assertInstanceOf('ClassOneTwo', $instance3->getVarTwo()->getOne());
		$this->assertEquals('bar', $instance3->getVarThree());
	}

	/**
	 * @test
	 * it should instance using bound implementations
	 */
	public function it_should_instance_using_bound_implementations() {
		ClassTwelve::reset();

		$container = new tad_DI52_Container();

		$container->bind('One', 'ClassOne');

		$f = $container->instance('ClassTwelve', array('One'));

		$instance1 = $f();

		$this->assertEquals(1, ClassTwelve::$builtTimes);
		$this->assertInstanceOf('ClassOne', $instance1->getVarOne());
	}

	/**
	 * @test
	 * it should allow overriding bound implementations in instance method
	 */
	public function it_should_allow_overriding_bound_implementations_in_instance_method() {
		ClassTwelve::reset();

		$container = new tad_DI52_Container();

		$container->bind('One', 'ClassOne');

		$f = $container->instance('ClassTwelve', array('ClassOneOne'));

		$instance1 = $f();

		$this->assertEquals(1, ClassTwelve::$builtTimes);
		$this->assertInstanceOf('ClassOneOne', $instance1->getVarOne());
	}

	/**
	 * @test
	 * it should allow referring bound slugs in instance method
	 */
	public function it_should_allow_referring_bound_slugs_in_instance_method() {
		ClassTwelve::reset();

		$container = new tad_DI52_Container();

		$container->bind('foo', 'ClassOne');

		$f = $container->instance('ClassTwelve', array('foo'));

		$instance1 = $f();

		$this->assertEquals(1, ClassTwelve::$builtTimes);
		$this->assertInstanceOf('ClassOne', $instance1->getVarOne());
	}

	/**
	 * @test
	 * it should use bound singletons as singletons in instance methods
	 */
	public function it_should_use_bound_singletons_as_singletons_in_instance_methods() {
		ClassTwelve::reset();

		$container = new tad_DI52_Container();

		$container->singleton('One', 'ClassOne');

		$f = $container->instance('ClassTwelve', array('One'));

		$this->assertInstanceOf('ClassOne', $f()->getVarOne());
		$this->assertSame($f()->getVarOne(), $f()->getVarOne());
	}

	/**
	 * @test
	 * it should resolve bound objects in instance method
	 */
	public function it_should_resolve_bound_objects_in_instance_method() {
		ClassTwelve::reset();

		$container = new tad_DI52_Container();

		$one = new ClassOne;
		$container->singleton('One', $one);

		$f = $container->instance('ClassTwelve', array('One'));

		$this->assertInstanceOf('ClassOne', $f()->getVarOne());
		$this->assertSame($one, $f()->getVarOne());
	}

	/**
	 * @test
	 * it should allow binding an instance in the container
	 */
	public function it_should_allow_binding_an_instance_in_the_container() {
		$container = new tad_DI52_Container();

		$container->bind('One', $container->instance('ClassOneTwo', array('sudo-foo')));

		$this->assertInstanceOf('ClassOneTwo', $container->make('One'));
		$this->assertEquals('sudo-foo', $container->make('One')->getFoo());
		$this->assertNotSame($container->make('One'), $container->make('One'));
	}

	/**
	 * @test
	 * it should allow binding an instance as a singleton in the container
	 */
	public function it_should_allow_binding_an_instance_as_a_singleton_in_the_container() {
		$container = new tad_DI52_Container();

		$container->singleton('One', $container->instance('ClassOneTwo', array('sudo-foo')));

		$this->assertInstanceOf('ClassOneTwo', $container->make('One'));
		$this->assertEquals('sudo-foo', $container->make('One')->getFoo());
		$this->assertSame($container->make('One'), $container->make('One'));
	}

	/**
	 * @test
	 * it should build the instance with the container if not specifying arguments
	 */
	public function it_should_build_the_instance_with_the_container_if_not_specifying_arguments() {
		$container = new tad_DI52_Container();

		$container->bind('One', 'ClassOneTwo');
		$f = $container->instance('One');

		$this->assertInstanceOf('ClassOneTwo', $f());
		$this->assertEquals('bar', $f()->getFoo());
		$this->assertNotSame($f(), $f());
	}

	/**
	 * @test
	 * it should use container binding settings when instancing
	 */
	public function it_should_use_container_binding_settings_when_instancing() {
		$container = new tad_DI52_Container();

		$container->singleton('One', 'ClassOneTwo');
		$f = $container->instance('One');

		$this->assertInstanceOf('ClassOneTwo', $f());
		$this->assertEquals('bar', $f()->getFoo());
		$this->assertSame($f(), $f());
	}

	/**
	 * @test
	 * it should fetch correct objects when re-binding
	 */
	public function it_should_fetch_correct_objects_when_re_binding() {
		$container = new tad_DI52_Container();

		$container->bind('One', 'ClassOneOne');

		$firstInstance = $container->make('ClassTwo');

		$this->assertInstanceOf('ClassOneOne', $firstInstance->getOne());

		$container->bind('One', 'ClassOne');

		$secondInstance = $container->make('ClassTwo');

		$this->assertInstanceOf('ClassOne', $secondInstance->getOne());
	}

	/**
	 * @test
	 * it should allow re-binding objects
	 */
	public function it_should_allow_re_binding_objects() {
		$container = new tad_DI52_Container();

		$container->bind('One', new ClassOneOne());

		$firstInstance = $container->make('ClassTwo');

		$this->assertInstanceOf('ClassOneOne', $firstInstance->getOne());

		$container->bind('One', new ClassOne());

		$secondInstance = $container->make('ClassTwo');

		$this->assertInstanceOf('ClassOne', $secondInstance->getOne());
	}

	/**
	 * @test
	 * it should allow for built objects to passed to instance
	 */
	public function it_should_allow_for_built_objects_to_passed_to_instance() {
		$container = new tad_DI52_Container();

		$obj = new ClassFour('foo');
		$instance = $container->instance($obj);

		$this->assertInstanceOf('ClassFour', $instance());
	}

	/**
	 * @test
	 * it should allow for built objects to be passed in callback
	 */
	public function it_should_allow_for_built_objects_to_be_passed_in_callback() {
		$container = new tad_DI52_Container();

		$obj = new ClassFour('foo');
		$callback = $container->callback($obj, 'methodTwo');

		$this->assertEquals(23, $callback());
	}

	/**
	 * @test
	 * it should allow for callback to be fed to instance
	 */
	public function it_should_allow_for_callback_to_be_fed_to_instance() {
		$container = new tad_DI52_Container();

		$callback = $container->callback('Factory', 'build');

		$instance = $container->instance($callback);

		$this->assertInstanceOf('ClassOne', $instance());
	}

	/**
	 * @test
	 * it should allow for instance to be fed to callback
	 */
	public function it_should_allow_for_instance_to_be_fed_to_callback() {
		$container = new tad_DI52_Container();

		$instance = $container->instance('Factory');

		$callback = $container->callback($instance, 'build');

		$this->assertInstanceOf('ClassOne', $callback());
	}

	/**
	 * @test
	 * it should allow registering instance callbacks on class names
	 */
	public function it_should_allow_registering_instance_callbacks_on_class_names() {
		$container = new tad_DI52_Container();

		$instance = $container->instance('ClassThirteen');

		$this->assertInstanceOf('ClassThirteen', $instance());
	}

	/**
	 * @test
	 * it should allow registering instance callbacks on classes with constructor arguments
	 */
	public function it_should_allow_registering_instance_callbacks_on_classes_with_constructor_arguments() {
		$container = new tad_DI52_Container();
		$container->bind('One', 'ClassOne');

		$instance = $container->instance('ClassFifteen');

		$this->assertInstanceOf('ClassFifteen', $instance());
	}

	/**
	 * @test
	 * it should allow registering callback callbacks on class names
	 */
	public function it_should_allow_registering_callback_callbacks_on_class_names() {
		$container = new tad_DI52_Container();

		$callback = $container->callback('ClassThirteen', 'doSomething');

		$this->assertEquals('IDidSomething', $callback());
	}

	/**
	 * @test
	 * it should allow registering callback callbacks on classes with constructor arguments
	 */
	public function it_should_allow_registering_callback_callbacks_on_classes_with_constructor_arguments() {
		$container = new tad_DI52_Container();
		$container->bind('One', 'ClassOne');

		$callback = $container->callback('ClassFifteen', 'doSomething');

		$this->assertEquals('IDidSomething', $callback());
	}

	/**
	 * It should return an interface argument default value if available and the interface is not bound
	 *
	 * @test
	 */
	public function it_should_return_an_interface_argument_default_value_if_available_and_the_interface_is_not_bound() {
		$defaultValue = 'foobar';

		$mockParameter = $this->getMockBuilder('ReflectionParameter')->disableOriginalConstructor()->getMock();
		$mockParameter->expects($this->once())->method('isDefaultValueAvailable')->will($this->returnValue(TRUE));
		$mockParameter->expects($this->once())->method('getDefaultValue')->will($this->returnValue($defaultValue));
		$mockClass = $this->getMockBuilder('ReflectionClass')->disableOriginalConstructor()->getMock();
		$mockClass->expects($this->once())->method('getName')->will($this->returnValue('SomeInterface'));
		$mockClass->expects($this->once())->method('isInstantiable')->will($this->returnValue(FALSE));
		$mockParameter->expects($this->any())->method('getClass')->will($this->returnValue($mockClass));

		$container = new tad_DI52_Container();
		$value     = $container->_getParameter($mockParameter);

		$this->assertEquals($defaultValue, $value);
	}

	/**
	 * It should throw an exception when trying to resolve unbound interface parameter with no default value
	 *
	 * @test
	 */
	public function it_should_throw_an_exception_when_trying_to_resolve_unbound_interface_parameter_with_no_default_value() {
		$mockParameter = $this->getMockBuilder('ReflectionParameter')->disableOriginalConstructor()->getMock();
		$mockParameter->expects($this->once())->method('isDefaultValueAvailable')->will($this->returnValue(false));
		$mockClass = $this->getMockBuilder('ReflectionClass')->disableOriginalConstructor()->getMock();
		$mockClass->expects($this->once())->method('getName')->will($this->returnValue('SomeInterface'));
		$mockClass->expects($this->once())->method('isInstantiable')->will($this->returnValue(false));
		$mockParameter->expects($this->any())->method('getClass')->will($this->returnValue($mockClass));

		$this->setExpectedException('RuntimeException');

		$container = new tad_DI52_Container();
		$container->_getParameter($mockParameter);
	}

	/**
	 * It should build not registered class dependencies anew each time
	 *
	 * @test
	 */
	public function it_should_build_not_registered_class_dependencies_anew_each_time() {
		$container = new tad_DI52_Container();

		$d1 = $container->make('Depending');
		$d2 = $container->make('Depending');

		$this->assertNotSame($d1,$d2);
		$this->assertNotSame($d1->getDependency(),$d2->getDependency());
	}
}
