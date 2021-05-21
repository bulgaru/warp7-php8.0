<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

// set attributes
$attributes = array('type' => 'checkbox', 'name' => $name);

// is checked ?
if ($node->attr('value') == $value) {
	$attributes = array_merge($attributes, array('checked' => 'checked'));
}

printf('<p class="uk-form-controls-condensed '.($node->attr("center") ? 'uk-text-center':'').'"><label><input %s/> %s</label></p>', $control->attributes(array_merge($node->attr(), $attributes), array('label', 'description', 'default', 'column')), $node->attr('label'));