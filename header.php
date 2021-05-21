<?php
/**
* @package   Master
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

// start output buffer to capture wp_head
ob_start();

wp_enqueue_script('jquery');
wp_head();

// start output buffer to capture content for use in footer.php
ob_start();