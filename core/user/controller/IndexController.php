<?php

namespace core\user\controller;

use core\base\controller\BaseController;

Class IndexController extends BaseController {
    /**
     * @throws \ReflectionException
     */
    protected $name;



    protected function inputData() {

        $str = '1234567890';

        $en_str = \core\base\model\Crypt::instance()->encrypt($str);

        $dec_str = \core\base\model\Crypt::instance()->decrypt($en_str);

        exit();

    }

}