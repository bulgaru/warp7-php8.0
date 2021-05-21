<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

return array(

    'helper' => array(
        'asset'       => 'Warp\Helper\AssetHelper',
        'assetfilter' => 'Warp\Helper\AssetfilterHelper',
        'check'       => 'Warp\Helper\CheckHelper',
        'checksum'    => 'Warp\Helper\ChecksumHelper',
        'dom'         => 'Warp\Helper\DomHelper',
        'event'       => 'Warp\Helper\EventHelper',
        'field'       => 'Warp\Helper\FieldHelper',
        'http'        => 'Warp\Helper\HttpHelper',
        'menu'        => 'Warp\Helper\MenuHelper',
        'path'        => 'Warp\Helper\PathHelper',
        'template'    => 'Warp\Helper\TemplateHelper',
        'useragent'   => 'Warp\Helper\UseragentHelper'
    ),

    'path' => array(
        'warp'    => array(__DIR__),
        'config'  => array(__DIR__.'/config'),
        'js'      => array(__DIR__.'/js', __DIR__.'/vendor/uikit/js'),
        'layouts' => array(__DIR__.'/layouts')
    ),

    'menu' => array(
        'pre'    => 'Warp\Menu\Menu',
        'post'   => 'Warp\Menu\Post',
        'nav'    => 'Warp\Menu\Nav',
        'navbar' => 'Warp\Menu\Navbar',
        'subnav' => 'Warp\Menu\Subnav'
    ),

    'branding' => 'Powered by <a href="http://www.yootheme.com">Warp Theme Framework</a>'

);
