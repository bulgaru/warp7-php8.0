<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

wp_footer();
do_action('get_footer', array());

// output tracking code
echo $this['config']->get('tracking_code');
