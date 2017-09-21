<?php

interface tad_DI52_ServiceProviderInterface
{
    /**
     * Binds and sets up implementations.
     */
    public function register();

    /**
     * Binds and sets up implementations at boot time.
     */
    public function boot();

    public function provides();

    /**
     * Whether the service provider will be a deferred one or not.
     *
     * @return bool
     */
    public function isDeferred();
}
