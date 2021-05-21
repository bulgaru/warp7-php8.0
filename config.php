<?php
/**
* @package   Master
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

return array(

    'path' => array(
        'theme'   => array(__DIR__),
        'js'      => array(__DIR__.'/js'),
        'css'     => array(__DIR__.'/css'),
        'less'    => array(__DIR__.'/less'),
        'layouts' => array(__DIR__.'/layouts')
    ),

    'less' => array(

        'vars' => array(
            'less:theme.less'
        ),

        'files' => array(
            '/css/theme.css' => 'less:theme.less',
            '/css/woocommerce.css' => 'less:woocommerce.less'
        )

    ),

    'cookie' => $cookie = md5(__DIR__),

    'customizer' => isset($_COOKIE[$cookie])

);
