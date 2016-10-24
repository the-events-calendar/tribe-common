<?php

class tad_DI52_Bindings_InstanceImplementation extends tad_DI52_Bindings_AbstractImplementation implements tad_DI52_Bindings_ImplementationInterface
{
    /**
     * Returns an object instance.
     *
     * @return mixed
     */
    public function instance()
    {
        return $this->implementation;
    }
}
