<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

printf('<p class="uk-form-controls-condensed"><select %s>', $control->attributes(compact('name')));

foreach ($node->children('option') as $option) {

	// set attributes
	$attributes = array('value' => $option->attr('value'));
    foreach ($option->attributes as $attr) {
        $attributes[$attr->name] = $attr->value;
    }

	// is selected ?
	if ($option->attr('value') == $value) {
		$attributes = array_merge($attributes, array('selected' => 'selected'));
	}

	printf('<option %s>%s</option>', $control->attributes($attributes), $option->text());
}

echo '</select>';

if ($description = $node->attr('description')) {
    printf('<span class="uk-form-help-inline">%s</span>', $node->attr('description'));
}

echo '</p>';