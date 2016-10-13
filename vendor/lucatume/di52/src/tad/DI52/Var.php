<?php


class tad_DI52_Var
{

    protected $value;

    /**
     * @var tad_DI52_Container
     */
    private $container;

    public function __construct(tad_DI52_Container $container)
    {
        $this->container = $container;
    }

    public static function create($value = null, tad_DI52_Container $container)
    {
        $instance = new self($container);
        $instance->set_value($value);

        return $instance;
    }

    public function get_value()
    {
        $value = null;
        if (is_array($this->value)) {
            $value = array_map(array($this, 'resolve_value'), $this->value);
        } else {
            $value = $this->value;
        }

        return $value;
    }

    public function set_value($value)
    {
        $this->value = $value;
    }

    protected function resolve_value($value)
    {
        return $this->container->resolve($value);
    }
}