<?php

namespace core\base\controller;


class BaseRoute
{

    use Singletone, BaseMethods;

    public static function routeDirection()
    {

        if (self::instance()->isAjax()) {

            exit((new BaseAjax())->route());

        }

        RouteController::instance()->route();

    }

}