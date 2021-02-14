<?php
/**
 * Created by PhpStorm.
 * User: Миша
 * Date: 30.11.2019
 * Time: 14:45
 */

namespace core\base\controller;


trait Singletone
{
    static private $_instance;

    private function __construct()
    {
    }

    private function __clone()
    {

    }

    static public function instance() {
        if(self::$_instance instanceof self) {
            return self::$_instance;
        }

        self::$_instance = new self;

        if(method_exists(self::$_instance, 'connect')) {
            self::$_instance->connect();
        }

        return self::$_instance;

    }

}