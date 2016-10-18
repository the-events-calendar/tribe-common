<?php


class tad_DI52_Ctor
{

    /** @var  string */
    protected $class;
    /** @var  string */
    protected $method;

    /** @var  tad_DI52_Arg[] */
    protected $args = array();

    /** @var  tad_DI52_Container */
    protected $container;

    /** @var  array */
    protected $calls;

    public static function create($class_and_method, array $args = array(), tad_DI52_Container $container)
    {
        $instance = new self;
        return self::instanceSetUp($class_and_method, $args, $container, $instance);
    }

    /**
     * @param $class_and_method
     * @param array $args
     * @param tad_DI52_Container $container
     * @param $instance
     * @return mixed
     */
    protected static function instanceSetUp($class_and_method, array $args, tad_DI52_Container $container, $instance)
    {
        /** @var tad_DI52_Ctor $instance */
        list($class, $method) = $instance->getClassAndMethod($class_and_method);
        $instance->class = $class;
        $instance->method = $method;
        $instance->container = $container;

        foreach ($args as $arg) {
            $instance->args[] = tad_DI52_Arg::create($arg, $instance->container);
        }

        return $instance;
    }

    public function __call($method_name, $arg1 = null)
    {
        $args = func_get_args();
        $args = $args[1];

        return $this->storeMethodAndArgs($method_name, $args);
    }

    public function callMethod($method_name)
    {
        $args = func_get_args();
        array_shift($args);

        return $this->storeMethodAndArgs($method_name, $args);
    }


    protected function getClassAndMethod($class_and_method)
    {
        if (!is_string($class_and_method)) {
            throw new InvalidArgumentException("Class and method should be a single string");
        }
        $frags = explode('::', $class_and_method);
        if (count($frags) > 2) {
            throw new InvalidArgumentException("One :: separator only");
        }

        return count($frags) === 1 ? array(
            $frags[0],
            '__construct'
        ) : $frags;
    }

    public function getObjectInstance()
    {
        $args = $this->getArgValues();

        $instance = $this->createInstance($args);

        $this->callFurtherMethods($instance);

        return $instance;
    }

    private function getArgValues()
    {
        $values = array();
        /** @var tad_DI52_Var $arg */
        foreach ($this->args as $arg) {
            $values[] = $arg->getValue();
        }

        return $values;
    }

    /**
     * @param $args
     *
     * @return mixed|object
     */
    protected function createInstance($args)
    {
        if ($this->method === '__construct') {
            $rc = new ReflectionClass($this->class);

            return !empty($args) ? $rc->newInstanceArgs($args) : $rc->newInstance();
        }

        return call_user_func_array(array(
            $this->class,
            $this->method
        ), $args);
    }

    /**
     * @param $instance
     */
    protected function callFurtherMethods($instance)
    {
        if (empty($this->calls)) {
            return;
        }
        foreach ($this->calls as $call) {
            $arg_values = array();
            /** @var tad_DI52_Var $arg */
            foreach ($call[1] as $arg) {
                $arg_values[] = $arg->getValue();
            }
            call_user_func_array(array(
                $instance,
                $call[0]
            ), $arg_values);
        }
    }

    protected function storeMethodAndArgs($method, array $args = array())
    {
        $_args = array();

        foreach ($args as $value) {
            $_args[] = tad_DI52_Arg::create($value, $this->container);
        }
        $this->calls[] = array(
            $method,
            $_args
        );

        return $this;
    }
}

