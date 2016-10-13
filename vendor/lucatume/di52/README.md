A PHP 5.2 compatible dependency injection container heavily inspired by [Laravel IOC](https://laravel.com/docs/5.0/container "Service Container - Laravel - The PHP Framework For Web Artisans").

## Installation
Use [Composer](https://getcomposer.org/) to require the library:

```bash
composer require lucatume/di52
```

## Code example

```PHP
// ClassOne.php
class ClassOne implements InterfaceOne {}

 // MysqlConnection.php
class MysqlConnection implements DbConnectionInterface {}

 // ClassThree.php
class ClassThree {
    public function __construct(InterfaceOne $one, DbConnectionInterface $two, wpdb $wpdb, $arg1 = 'foo'){
       // ... 
    }
}

// in the application bootstrap file
$container = new tad_DI52_Container();

$container->bind('InterfaceOne', 'ClassOne');
$container->singleton('DbConnectionInterface', 'MysqlConnection');
global $wpdb;
$container['wpdb'] = $wpdb;

$three = $container['ClassThree'];
```
    
## Binding and resolving implementations

### Concrete class binding
The container can be set to resolve a request for an interface or a concrete class to a specific class:

```PHP
$container->bind('InterfaceOne', 'ClassOne');

$one = $container->make('InterfaceOne');
$two = $container->make('InterfaceOne');
// $one !== $two

$container->singleton('InterfaceOne', 'ClassOne');

$three = $container->make('InterfaceOne');
$four = $container->make('InterfaceOne');
// $three === $four;
```
    
The singleton case can be replicated using the ArrayAccess API:

```PHP
$container->['InterfaceOne'] = 'ClassOne';

$three = $container->make('InterfaceOne');
$four = $container->make('InterfaceOne');
// $three === $four;
```
    
### Callback binding
The container can be told to resolve a request for an interface or concrete class to a callback function:

```PHP
$container->bind('InterfaceOne', function(){
    return new ClassOne();
});

$one = $container->make('InterfaceOne');
$two = $container->make('InterfaceOne');
// $one !== $two

$container->singleton('InterfaceOne', function(){
    return new ClassOne();
});

$three = $container->make('InterfaceOne');
$four = $container->make('InterfaceOne');
// $three === $four;
```
    
The singleton case can be replicated using the ArrayAccess API:

```PHP
$container['InterfaceOne'] = function(){
    return new ClassOne();
};

$three = $container->make('InterfaceOne');
$four = $container->make('InterfaceOne');
// $three === $four;
```
   
### Instance binding
Finally the container can resolve the request for an interface or class implementation to an object instance:
    
```PHP
 $classOne = new ClassOne();
$container->bind('InterfaceOne', $classOne);

$one = $container->make('InterfaceOne');
$two = $container->make('InterfaceOne');
// $one === $two

$container->singleton('InterfaceOne', $classOne);

$three = $container->make('InterfaceOne');
$four = $container->make('InterfaceOne');
// $three === $four;
```

The singleton case can be replicated using the ArrayAccess API:

```PHP
$container['InterfaceOne'] = $classOne;

$three = $container->make('InterfaceOne');
$four = $container->make('InterfaceOne');
// $three === $four;
```

### Decorator binding
Using the [decorator pattern](https://en.wikipedia.org/wiki/Decorator_pattern "Decorator pattern") with the container might lead to infinite circular resolution loops due to the container trying to provide a class with an instance of itself.  Given the following classes:

```PHP
class A implements SomeInterface {
    public function out(){
        return 'foo';
    }
}

class B implements SomeInterface {
    
    protected $interface;

    public function __construct(SomeInterface $interface){
        $this->interface = $interface;
    }

    public function out(){
        return $this->interface->out() . 'bar';
    }
}

class C implements SomeInterface {
    
    protected $interface;

    public function __construct(SomeInterface $interface){
        $this->interface = $interface;
    }

    public function out(){
        return 'baz' . $this->interface->out();
    }
}
```

the container could bind the decorator chain to an interface (or implementation for the matter) using a closure:

```PHP
$container->bind('SomeInterface', function($container){
    $base = $container->make('A');

    return new C(new B($base));
});
```

If PHP 5.2 compatibility is required the container provides the `bindDecorators` method; the code below is equivalent, in result, to the closure based one above:

```PHP
$decoratorsChain = array('C', 'B', 'A');
$container->bindDecorators('SomeInterface', $decoratorsChain);
```

Duplicating what's available for any other class the two implementations can work for singletons too.  
The closure based solution:

```PHP
$container->singleton('SomeInterface', function($container){
    $base = $container->make('A');

    return new C(new B($base));
});
```
and the PHP 5.2 compatible one:

```PHP
$decoratorsChain = array('C', 'B', 'A');
$container->singletonDecorators('SomeInterface', $decoratorsChain);
```

### Resolving unbound implementations
The container will do its best to return an instance even when no bindigs about it have been set: if all the dependencies of a class are concrete classes or primitives with a default value than the container will take care of that:
    
```PHP
// file ClassFour.php
class ClassFour {
    public function __construct(ClassOne $one, ClassTwo $two, $arg1 = 'foo', $arg2 = 23){
        // ...
    }
}

// in the application bootstrap file
$instance = $container->make('ClassFour');
```
    
or using the array access API (being unbound it will **not** work as a singleton)

```PHP
$instance = $container['ClassFour'];
$instanceTwo = $container['ClassFour'];

// $instance == $instanceTwo;
// $instance !== $instanceTwo;
```

### Tagging
A class constructor might need to be injected an array of implementations extending a concrete class or implementing an interface, take the class below

```PHP
class Dispatcher implements DispatcherInterface {
    /**
     * A list of dispatch destinations.
     * @var ListenerInterface[]
     */
    protected $destinations = array();
    public function __construct(array $destinations){
        $this->destinations = $destinations;
    }
}
```

And have the class bound in the container like this
    
```PHP
$container->bind('SystemLogListener', new SystemLogListener);
$container->bind('MailListener', new MailListener);
$container->bind('NoticeListener', new NoticeListener);

$container->tag(array('SystemLogListener', 'MailListener', 'NoticeListener'), 'listeners');

$dispatcher = new Dispatcher($container->tagged('listeners'));

$container->bind('DispatcherInterface', $dispatcher);
```

## Service Providers
To allow for a modular set up of the application the container allows for service providers.  
A service provider is a concrete class implementing the `tad_DI52_ServiceProviderInterface` interface or extending the `tad_DI52_ServiceProvider` class.  

```PHP
class MessageServiceProvider extends tad_DI52_ServiceProvider {

    public function register() {
        $container->bind('SystemLogListener', new SystemLogListener);
        $container->bind('MailListener', new MailListener);
        $container->bind('NoticeListener', new NoticeListener);

        $container->tag(array('SystemLogListener', 'MailListener', 'NoticeListener'), 'listeners');
        
        $dispatcher = new Dispatcher($container->tagged('listeners'));

        $container->bind('DispatcherInterface', $dispatcher);
    }

}

// bootstrap.php  
$container = new tad_DI52_Container();

$container->register('MessageServiceProvider');

$dispatcher = $container->make('DispatcherInterface');
```

### Boot
The service provider `register` method will be called immediatly when registering the service provider but bindings and operations that might require to run when all of the container bindings are set can be defined in the `boot` method.  
    
```PHP    
class RenderEngineProvider extends tad_DI52_ServiceProvider {

    public function boot() {
        $renderEngine = new RenderEngineOne();
        $views = $this->container->tagged('views');

        foreach($views as $view) {
            $view->setRenderEngine($renderEngine);
        }
    }

}


// bootstrap.php    
$container->register('RenderEngineProvider');

// more container registration..

$container->boot();
```

### Deferred service providers
Some service providers might define lengthy operations or require expensive bound implementations that are not required every time the application runs; to allow for that a provider can be defined as deferred.  
Deferred service providers will only register if one of the implementations they provide is required.  
To define a service provider as deferred extend the `tad_DI52_ServiceProvider` class and override the `deferred` property and the `provides` method.

```PHP
class DeferredServiceProvider extends tad_DI52_ServiceProvider {
    
    protected $deferred = true;

    public function provides(){
        return array(
            'InterfaceOne',
            'InterfaceTwo',
            'InterfaceThree',
            'InterfaceFour',
            'InterfaceFive'
        );
    }

    public function register() {
        $this->container->singleton('InterfaceOne', 'ClassOne');
        $this->container->singleton('InterfaceTwo', 'ClassTwo');
        $this->container->bind('InterfaceThree', 'ClassThree');
        $this->container->bind('InterfaceFour', 'ClassFour);
        $this->container->bind('InterfaceFive', 'ClassFive');
    }

}
```

## Verbose resolution
Beside the binding and automatic resolution the container implements another API using its own symbol language; the two APIs can be used together and independently.

### Setting and retrieving variables
In the instance that the need for a shared variable arises the container allows for easy storing and retrieving of variables of any type:

```PHP
$c = new tad_DI52_Container();

$c->set_var('someVar', 'foo');

// prints 'foo'
print($c->get_var('someVar'));
```

The opinionated path the container takes about variables, and objects as well, is that those should be set once and later modification will not be allowed; parametrized arguments can be used for that

```PHP
$c = new tad_DI52_Container();

$c->set_var('someVar', 'foo');

// prints 'foo'
print($c->get_var('someVar'));

$c->set_var('someVar', 'bar');

// prints 'foo'
print($c->get_var('someVar'));
```

### Setting and getting constructor methods
The container is a dumb one not taking any guess about what an object requires for its instantiation and assuming all the needed args are supplied. This means that concrete class names and methods must be supplied to it, along with needed arguments, to make it work. 
The most basic constructor registration for a class like 

```PHP
class SomeClass {
    
    public $one;
    public $two;

    public function __construct(){
        $this->one = new One();
        $this->two = 'foo';
}
```

its contstructor can be set in the container like this

```PHP
$c = new tad_DI52_Container();

$c->set_ctor('some class', 'SomeClass');

$someClass = $c->make('some class');

// prints 'foo';
print($someClass->two);
```

But a dependency injection container is made to avoid such code in the first place and a rewritten `SomeClass` reads like

```PHP
class SomeClass {
    
    public $one;
    public $two;

    public function __construct(One $one, $two){
        $this->one = $one;
        $this->two = $two;
}
```

and *might* take advantage of the container like this

```PHP
$c = new tad_DI52_Container();

$c->set_ctor('some class', 'SomeClass', new One(), 'foo');

$someClass1 = $c->make('some class');
$someClass2 = $c->make('some class');

// prints 'foo';
print($someClass1->two);

// not same instance of SomeClass
$someClass1 !== $someClass2;

// but shared same instance of One
$someClass1->one === $someClass2->one;
```

but the same instance of `One` will be shared between all instances of `SomeClass`.

### Referring registered variables and constructors
The possibility to refer previously registered variables and constructors exists using some special markers for the constructor arguments; given the same class above the code is rewritten to

```PHP
$c = new tad_DI52_Container();

$c->set_ctor('one', 'One');
$c->set_var('string', 'foo');

$c->set_ctor('some class', 'SomeClass', '@one', '#string');

$someClass1 = $c->make('some class');
$someClass2 = $c->make('some class');

// prints 'foo';
print($someClass1->two);

// not same instance of SomeClass
$someClass1 !== $someClass2;

// not same instance of One
$someClass1->one !== $someClass2->one;
```

### Specifying static constructor methods 
If a class instance should be created using a static constructor as in the case below

```PHP
class AnotherClass {

    public $one;
    public $two;

    public static function one(One $one, $two){
        $i = new self;

        $i->one = $one;
        $i->two = $two;

        return $i;
    }
}
```

then the registration of the class constructor in the container is possible appending the static method name to the class name like this

```PHP
$c = new tad_DI52_Container();

$c->set_ctor('one', 'One');
$c->set_var('string', 'foo');

$c->set_ctor('another class', 'AnotherClass::one', '@one', '#string');

$anotherClass = $c->make('another class');
```

### Calling further methods
There might be the need to call some further methods on the instance after it has been created, the container allows for that

```PHP
$c = new tad_DI52_Container();

$c->set_ctor('one', 'One');
$c->set_var('string', 'foo');

$c->set_ctor('still another class', 'StillAnotherClass')
    ->setOne('@one')
    ->setString('#string');

$anotherClass = $c->make('still another class');

// the same as calling
$one = new One();
$string = 'foo';

$i = new StillAnotherClass();
$i->setOne($one);
$i->setString($string);
```

If the method to call is *covered* by the container methods or there is the desire for a more explicit interface then the `call_method` method can be used; in the example above

```PHP
$c->set_ctor('still another class', 'StillAnotherClass')
    ->call_method('setOne', '@one')
    ->call_method('setString', '#string');
```

### Singleton
Singleton is a notorious and nefarious anti-pattern (and a testing sworn enemy) and the container allows for *sharing* of the same object instance across any call to the `make` method like this

```PHP
$c = new tad_DI52_Container();

$c->set_shared('singleton', 'NotASingleton');

$i1 = $c->make('singleton');
$i2 = $c->make('singleton');

$i1 === $i2;
```

Shared instances can be referred in other registered constructors using the `@` as well.

## Verbose resolution - ArrayAccess API
The array access API leaves some of the flexibility of the object API behind to make some operations quicker.  
Any instance set using the array access API will be a shared one, the code below is equivalent

```PHP
$c = new tad_DI52_Container();

$c['some-class'] = 'SomeClass';

// is the same as

$c->set_shared('some-class','SomeClass');
```

The same syntax is available for variables too

```PHP
$c['some-var'] = 'some string';

// is the same as

$c->set_var('some-var','some string');
```

on the same page more complex constructors can be set

```PHP
$c->set_shared('some-class', 'SomeClass::instance', 'one', 23);

// is the same as

$c['some-class'] = array('SomeClass::instance', 'one', 23);
```

Getting hold of a shared object instance or a var follows the expected path

```PHP
$someClass = $c['some-class'];
$someVar = $c['some-var'];
```

Finally registered constructors and variables can be referenced later in other registered constructors

```PHP
$c['some-dependency'] = 'DependencyClass';
$c['some-var'] = 'foo';
$c['some-class'] = array('SomeClass', '@some-dependency', '#some-var');
```

### Alternative notation for variables
Variables can be indicated using the `%varName%` notation as an alternative to the `#varName` one; the example above could be rewritten like this

```PHP
$c['some-dependency'] = 'DependencyClass';
$c['some-var'] = 'foo';
$c['some-class'] = array('SomeClass', '@some-dependency', '%some-var%');
```

### Array resolution    
Should a list of container instantiated objects or values be needed the container will allow for that and will properly resolve; using the Array Access API

```PHP
    $container = new DI();
    $container['ctor-one'] = 'ClassOne';
    $container['ctor-two'] = 'ClassTwo';
    $container['var-one'] = 'foo';
    $container['var-two'] = 'baz';

    $container['a-list-of-stuff'] = array('@ctor-one', '@ctor-two', '#var-one', '#var-two', 'just a string', 23);

    $this->assertInternalType('array', $container['a-list-of-stuff']);
    $this->assertInstanceOf('ClassOne', $container['a-list-of-stuff'][0]);
    $this->assertInstanceOf('ClassTwo', $container['a-list-of-stuff'][1]);
    $this->assertEquals('foo', $container['a-list-of-stuff'][2]);
    $this->assertEquals('baz', $container['a-list-of-stuff'][3]);
    $this->assertEquals('just a string', $container['a-list-of-stuff'][4]);
    $this->assertEquals(23, $container['a-list-of-stuff'][5]);
```

This can only be done using the array access API.
