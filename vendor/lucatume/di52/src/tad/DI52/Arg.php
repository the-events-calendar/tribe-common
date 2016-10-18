<?php


class tad_DI52_Arg
{

    protected $arg;

    /** @var  tad_DI52_Container */
    protected $container;

    public static function create($arg, tad_DI52_Container $container)
    {

        list($type, $value) = self::getArgDetails($arg);

        switch ($type) {
            case '@':
                $instance = new tad_DI52_ReferredInstanceArgValue($value, $container);
                break;
            case '%':
                $instance = new tad_DI52_ReferredVarArgValue(substr($value, 0, -1), $container);
                break;
            case '#':
                $instance = new tad_DI52_ReferredVarArgValue($value, $container);
                break;
            case '~':
                $instance = tad_DI52_NewInstanceArgValue::create($value);
                break;
            default:
                $instance = tad_DI52_RealArgValue::create($value);
                break;
        }

        return $instance;
    }

    private static function getArgDetails($arg)
    {
        $matches = array();
        $is_referred_value = is_string($arg) && preg_match("/^(#|@|~|%)(.*)(%)*$/", $arg, $matches);
        if ($is_referred_value) {
            $type = $matches[1];
            $value = $matches[2];
        } else {
            $type = 'real_value';
            $value = $arg;
        }

        return array($type, $value);
    }
}
