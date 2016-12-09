<?php

class tad_DI52_Bindings_Resolver implements tad_DI52_Bindings_ResolverInterface
{
    /**
     * @var tad_DI52_Bindings_AbstractImplementation[]
     */
    protected $bindings = array();

    /**
     * @var array
     */
    protected $singletons = array();

    /**
     * @var array
     */
    protected $resolvedSingletons = array();

    /**
     * @var array
     */
    protected $tagged = array();

    /**
     * @var tad_DI52_ServiceProviderInterface[]
     */
    protected $serviceProviders = array();

    /**
     * @var array
     */
    protected $deferredServiceProviders = array();

    /**
     * @var array
     */
    protected $singletonAliases = array();

    /**
     * @var array
     */
    protected $singletonImplementations = array();

    /**
     * @var array
     */
    protected $singletonImplementationObjects = array();

    /**
     * @var array
     */
    protected $decoratorsChain = array();

    /**
     * @var bool
     */
    protected $resolvingDecorator = false;

    /**
     * @var tad_DI52_Container
     */
    protected $container;

    /**
     * @var array
     */
    protected $reflectors = array();

    /**
     * @var string
     */
    protected $currentlyResolvingClassOrInterface;

    /**
     * @var array
     */
    protected $classNameBindings = array();

    /**
     * @var array
     */
    protected $afterBuildMethods = array();

    /**
     * @param tad_DI52_Container $container
     */
    public function __construct(tad_DI52_Container $container)
    {
        $this->container = $container;
    }

    /**
     * Tags an array of implementation bindings.
     *
     * @param array $implementationsArray
     * @param string $tag
     */
    public function tag(array $implementationsArray, $tag)
    {
        if (!is_string($tag)) {
            throw new InvalidArgumentException('Tag must be a string.');
        }
        $this->tagged[$tag] = $implementationsArray;
    }

    /**
     * Retrieves an array of bound implementations resolving them.
     *
     * @param string $tag
     * @return array An array of resolved bound implementations.
     */
    public function tagged($tag)
    {
        if (!is_string($tag)) {
            throw new InvalidArgumentException('Tag must be a string.');
        }
        if (!isset($this->tagged[$tag])) {
            throw new InvalidArgumentException("No implementations array was tagged [$tag]");
        }

        return array_map(array($this, 'resolve'), $this->tagged[$tag]);
    }

    /**
     * Registers a service provider implementation.
     *
     * @param string $serviceProviderClass
     */
    public function register($serviceProviderClass)
    {
        if (!class_exists($serviceProviderClass)) {
            throw new InvalidArgumentException("Service provider class [{$serviceProviderClass}] does not exist.");
        }
        $class_implements = class_implements($serviceProviderClass);
        if (!isset($class_implements['tad_DI52_ServiceProviderInterface'])) {
            throw new InvalidArgumentException("Service provider class [{$serviceProviderClass}] is not an implementation of the [tad_DI52_ServiceProviderInterface] interface.");
        }

        /** @var tad_DI52_ServiceProviderInterface $serviceProvider */
        $serviceProvider = new $serviceProviderClass($this->container);
        $this->serviceProviders[] = $serviceProvider;

        if ($serviceProvider->isDeferred()) {
            $providedClasses = $serviceProvider->provides();
            $buffer = array_combine($providedClasses, array_fill(0, count($providedClasses), $serviceProvider));
            $this->deferredServiceProviders = array_merge($this->deferredServiceProviders, $buffer);
        } else {
            $serviceProvider->register();
        }

        return $serviceProvider;
    }

    /**
     * Boots up the application calling the `boot` method of each registered service provider.
     */
    public function boot()
    {
        if (empty($this->serviceProviders)) {
            return array();
        }
        return array_map(array($this, 'bootServiceProvider'), $this->serviceProviders);
    }

    /**
     * Checks whether if an interface or class has been bound to a concrete implementation.
     *
     * @param string $classOrInterface
     * @return bool
     */
    public function isBound($classOrInterface)
    {
        if (!is_string($classOrInterface)) {
            throw new InvalidArgumentException('Class or interface must be a string');
        }

        return isset($this->bindings[$classOrInterface]);
    }

    /**
     * Checks whether a tag group exists in the container.
     *
     * @param string $tag
     * @return bool
     */
    public function hasTag($tag)
    {
        if (!is_string($tag)) {
            throw new InvalidArgumentException('Tag must be a string');
        }

        return isset($this->tagged[$tag]);
    }

    /**
     * Binds a chain of decorators to a class or interface.
     *
     * @param $classOrInterface
     * @param array $decorators
     */
    public function bindDecorators($classOrInterface, array $decorators)
    {
        $this->bind($classOrInterface, end($decorators));
        $this->decoratorsChain[$classOrInterface] = $decorators;
    }

    /**
     * Binds an interface or class to an implementation.
     *
     * @param string $classOrInterface
     * @param string $implementation
     * @param array $afterBuildMethods
     */
    public function bind($classOrInterface, $implementation, array $afterBuildMethods = null)
    {
        $this->realBind($classOrInterface, $implementation, false, $afterBuildMethods);
    }

    /**
     * @param string $classOrInterface
     * @param mixed $implementation
     * @param bool $isSingleton
     * @param array $afterBuildMethods
     */
    protected function realBind($classOrInterface, $implementation, $isSingleton = false, array $afterBuildMethods = null)
    {
        $isCallbackImplementation = is_callable($implementation);
        $isInstanceImplementation = is_object($implementation);

        if (is_string($implementation)) {
            $this->classNameBindings[$classOrInterface] = $implementation;
        }

        if (!empty($afterBuildMethods)) {
            $this->afterBuildMethods[$classOrInterface] = $afterBuildMethods;
        }

        $implementation_object = null;

        if ($isSingleton && $index = array_search($implementation, $this->singletonImplementations)) {
            $implementation_object = $this->singletonImplementationObjects[$index];
        } elseif (is_string($implementation)) {
            $implementation_object = new tad_DI52_Bindings_ConstructorImplementation($implementation, $this->container, $this);
        } elseif ($isCallbackImplementation) {
            $implementation_object = new tad_DI52_Bindings_CallbackImplementation($implementation, $this->container, $this);
        } elseif ($isInstanceImplementation) {
            $implementation_object = new tad_DI52_Bindings_InstanceImplementation($implementation, $this->container, $this);
        } else {
            throw new InvalidArgumentException("Implementation should be a class name, a callback or an object instance.");
        }

        $this->bindings[$classOrInterface] = $implementation_object;

        if ($isSingleton) {
            if (empty($index)) {
                $index = microtime();
                $this->singletonImplementations[$index] = $implementation;
                $this->singletonImplementationObjects[$index] = $implementation_object;
            }
            $this->singletonAliases[$classOrInterface] = $index;
        }
    }

    /**
     * Binds a chain of decorators to a class or interface to be returned as a singleton.
     *
     * @param $classOrInterface
     * @param array $decorators
     */
    public function singletonDecorators($classOrInterface, $decorators)
    {
        $this->singleton($classOrInterface, end($decorators));
        $this->decoratorsChain[$classOrInterface] = $decorators;
    }

    /**
     * Binds an interface or class to an implementation and will always return the same instance.
     *
     * @param string $classOrInterface
     * @param string $implementation
     * @param array $afterBuildMethods
     */
    public function singleton($classOrInterface, $implementation, array $afterBuildMethods = null)
    {
        $this->realBind($classOrInterface, $implementation, true, $afterBuildMethods);
        $this->singletons[$classOrInterface] = $classOrInterface;
    }

    protected function bootServiceProvider(tad_DI52_ServiceProviderInterface $serviceProvider)
    {
        return $serviceProvider->boot();
    }

    /**
     * @param ReflectionParameter $parameter
     *
     * @return mixed The resolved dependency.
     */
    protected function resolveDependency(ReflectionParameter $parameter)
    {
        $dependency = $parameter->getClass();
        $slugAliasDependency = !empty($dependency)
            && class_exists($dependency->getName())
            && $resolvedDependency = $this->resolveSlugAliasedDependency($dependency->getName());
        if (!$slugAliasDependency) {
            try {
                $resolvedDependency = $dependency === null ? $this->resolveNonClass($parameter) : $this->resolve($dependency->name);
            } catch (InvalidArgumentException $e) {
                if (!$parameter->isDefaultValueAvailable()) {
                    throw $e;
                }
                $resolvedDependency = $parameter->getDefaultValue();
            }
        }

        return $resolvedDependency;
    }

    protected function resolveSlugAliasedDependency($className)
    {
        if (empty($className)) {
            return false;
        }

        if (in_array($className, $this->classNameBindings)) {
            return $this->resolve(array_search($className, $this->classNameBindings));
        }

        return false;
    }

    /**
     * Returns an instance of the class or object bound to an interface.
     *
     * @param string $classOrInterface A fully qualified class or interface name.
     * @return mixed
     */
    public function resolve($classOrInterface)
    {

        if (isset($this->deferredServiceProviders[$classOrInterface])) {
            $serviceProvider = $this->deferredServiceProviders[$classOrInterface];
            $serviceProvider->register();
        }

        $isBound = isset($this->bindings[$classOrInterface]);

        if (!($isBound || class_exists($classOrInterface))) {
            throw new InvalidArgumentException("[{$classOrInterface}] is not a bound slug, implementation or existing class.");
        }

        $isSingleton = isset($this->singletons[$classOrInterface]);
        if ($isSingleton && isset($this->resolvedSingletons[$classOrInterface])) {
            return $this->resolvedSingletons[$classOrInterface];
        }

        $isDecoratorChain = !$this->resolvingDecorator && isset($this->decoratorsChain[$classOrInterface]);

        if (!$isBound) {
            $resolved = $this->resolveUnbound($classOrInterface);
            return $resolved;
        }

        $resolved = $isDecoratorChain ? $this->resolveBoundDecoratorChain($classOrInterface) : $this->resolveBound($classOrInterface);

        if (isset($this->afterBuildMethods[$classOrInterface])) {
            foreach ($this->afterBuildMethods[$classOrInterface] as $method) {
                call_user_func(array($resolved, $method));
            }
        }

        if ($isSingleton) {
            $this->resolvedSingletons[$classOrInterface] = $resolved;
            $index = $this->singletonAliases[$classOrInterface];
            foreach ($this->singletonAliases as $alias => $aliasIndex) {
                if ($alias !== $classOrInterface && $aliasIndex === $index) {
                    $this->resolvedSingletons[$alias] = $resolved;
                }
            }
        }

        return $resolved;
    }

    protected function resolveUnbound($classOrInterface)
    {
        if (isset($this->reflectors[$classOrInterface])) {
            list($reflector, $constructor, $parameters) = $this->reflectors[$classOrInterface];
        } else {
            $reflector = new ReflectionClass($classOrInterface);
            if (!$reflector->isInstantiable()) {
                throw new Exception('[' . $classOrInterface . '] is not instantiatable.');
            }
            $constructor = $reflector->getConstructor();
            $parameters = $constructor !== null ? $constructor->getParameters() : array();

            $this->reflectors[$classOrInterface] = array($reflector, $constructor, $parameters);
        }

        if ($constructor === null) {
            return new $classOrInterface;
        }

        $dependencies = $this->getDependencies($parameters, $classOrInterface);

        return $reflector->newInstanceArgs($dependencies);
    }

    /**
     * @param ReflectionParameter[] $parameters
     * @param string $classOrInterface
     * @return array
     */
    protected function getDependencies(array $parameters, $classOrInterface)
    {
        $this->currentlyResolvingClassOrInterface = $classOrInterface;

        $dependencies = array_map(array($this, 'resolveDependency'), $parameters);

        $this->currentlyResolvingClassOrInterface = false;

        return $dependencies;
    }

    protected function resolveBoundDecoratorChain($classOrInterface)
    {
        $chain = $this->decoratorsChain[$classOrInterface];
        $base = array_pop($chain);
        $this->bind($classOrInterface, $base);
        $resolvedDecorator = null;

        $this->resolvingDecorator = true;

        foreach (array_reverse($chain) as $decorator) {
            $resolvedDecorator = $this->resolveUnbound($decorator);
            $this->bind($classOrInterface, $resolvedDecorator);
        }

        $this->resolvingDecorator = false;

        return $resolvedDecorator;
    }

    protected function resolveBound($classOrInterface)
    {
        $implementation = $this->bindings[$classOrInterface];
        return $implementation->getImplementation() === $classOrInterface ? $this->resolveUnbound($classOrInterface) : $implementation->instance();
    }

    protected function resolveNonClass(ReflectionParameter $parameter)
    {
        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }

        throw new InvalidArgumentException("Cannot resolve parameter [$parameter->name] of class [$this->currentlyResolvingClassOrInterface]: default value for primitive misssing");
    }
}
