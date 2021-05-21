<?php
/**
* @package   Master
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

// get warp
$warp = require(__DIR__.'/warp.php');

// load main theme file, located in /layouts/theme.php
echo $warp['template']->render('theme');
