<?php


class tad_DI52_Singleton extends tad_DI52_Ctor
{

    /**
     * @var mixed
     */
    protected $instance;


    public function get_object_instance()
    {
        if (empty($this->instance)) {
            $instance = parent::get_object_instance();
            $this->instance = $instance;
        }

        return $this->instance;
    }

    public static function create($class_and_method, array $args = array(), tad_DI52_Container $container)
    {
        $instance = new self;
        return self::instance_set_up($class_and_method, $args, $container, $instance);
    }
}