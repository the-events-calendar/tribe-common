<?php

class tad_DI52_Bindings_ConstructorImplementation extends tad_DI52_Bindings_AbstractImplementation implements tad_DI52_Bindings_ImplementationInterface
{
    /**
     * Returns an object instance.
     *
     * @return mixed
     */
    public function instance()
    {
        return $this->resolver->resolve($this->implementation);
    }
}
