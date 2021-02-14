<?php
/**
 * Created by PhpStorm.
 * User: Миша
 * Date: 04.11.2019
 * Time: 20:35
 */

namespace core\base\settings;

class ShopSettings
{

    use BaseSettings;

    private $routes = [
        'plugins' => [
            'dir' => false,
            'routes' => [

            ]
        ]
    ];

    private $templateArr = [
        'text' => ['price', 'short', 'name'],
        'textarea' => ['goods_content']
    ];

}