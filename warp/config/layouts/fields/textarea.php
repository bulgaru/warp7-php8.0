<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

printf('<textarea %s>%s</textarea>', $control->attributes(array_merge($node->attr(), compact('name')), array('label', 'description', 'default')), htmlspecialchars($value));