<?php

interface tad_DI52_Bindings_ImplementationInterface
{
    /**
     * tad_DI52_Bindings_ConstructorImplementation constructor.
     * @param string $implementation
     * @param tad_DI52_Container $container
     * @param tad_DI52_Bindings_ResolverInterface $resolver
     */
    public function __construct($implementation, tad_DI52_Container $container, tad_DI52_Bindings_ResolverInterface $resolver);

    /**
     * Returns an object instance.
     *
     * @return mixed
     */
    public function instance();

    /**
     * @return mixed
     */
    public function getImplementation();
}
