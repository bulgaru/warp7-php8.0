<?php
/**
* @package   Master
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

use Warp\Warp;
use Warp\Autoload\ClassLoader;
use Warp\Config\Repository;

global $warp;

if (!$warp) {

    require_once(__DIR__.'/warp/src/Warp/Autoload/ClassLoader.php');

    // set loader
    $loader = new ClassLoader;
    $loader->add('Warp', __DIR__.'/warp/src');
    $loader->add('Warp\Wordpress', __DIR__.'/warp/systems/wordpress/src');
    $loader->register();

    // set config
    $config = new Repository;
    $config->load(__DIR__.'/warp/config.php');
    $config->load(__DIR__.'/warp/systems/wordpress/config.php');
    $config->load(__DIR__.'/config.php');

    // set warp
    $warp = new Warp(compact('loader', 'config'));
    $warp['system']->init();
}

return $warp;