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
        $instance->setValue($value);

        return $instance;
    }

    public function getValue()
    {
        $value = null;
        if (is_array($this->value)) {
            $value = array_map(array($this, 'resolveValue'), $this->value);
        } else {
            $value = $this->value;
        }

        return $value;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    protected function resolveValue($value)
    {
        return $this->container->resolve($value);
    }
}
