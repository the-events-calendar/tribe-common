<?php

class tad_DI52_Bindings_CallbackImplementation extends tad_DI52_Bindings_AbstractImplementation implements tad_DI52_Bindings_ImplementationInterface
{
    /**
     * Returns an object instance.
     *
     * @return mixed
     */
    public function instance()
    {
        return call_user_func($this->implementation, $this->resolver, $this->container);
    }
}
