<?php

class ProviderOne extends tad_DI52_ServiceProvider
{
    /**
     * Binds and sets up implementations.
     */
    public function register()
    {
        $this->container->bind('foo', 23);
    }
}

class DeferredProviderOne extends tad_DI52_ServiceProvider
{

    protected $deferred = true;

    /**
     * Binds and sets up implementations.
     */
    public function register()
    {
        $this->container->bind('foo', 23);
    }

}

class DeferredProviderTwo extends tad_DI52_ServiceProvider
{

    protected static $wasRegistered = false;
    protected $deferred = true;

    public static function wasRegistered()
    {
        return self::$wasRegistered;
    }

    public static function reset()
    {
        self::$wasRegistered = false;
    }

    public function provides()
    {
        return array('One');
    }


    /**
     * Binds and sets up implementations.
     */
    public function register()
    {
        $this->container->bind('One', 'ClassOne');
        self::$wasRegistered = true;
    }

}

class ProviderThree extends tad_DI52_ServiceProvider
{

    /**
     * Binds and sets up implementations.
     */
    public function boot()
    {
        $this->container->bind('One', 'ClassOne');
    }

    /**
     * Binds and sets up implementations.
     */
    public function register()
    {
        // no-op
    }
}
