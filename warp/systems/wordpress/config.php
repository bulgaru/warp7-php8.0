<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

return array(

    'helper' => array(
       'system'  => 'Warp\Wordpress\Helper\SystemHelper',
       'option'  => 'Warp\Wordpress\Helper\OptionHelper',
       'widgets' => 'Warp\Wordpress\Helper\WidgetsHelper'
    ),

    'path' => array(
        'config'  => array(__DIR__.'/config'),
        'layouts' => array(__DIR__.'/layouts')
    ),

    'menu' => array(
        'pre' => 'Warp\Wordpress\Menu\Pre'
    )

);
