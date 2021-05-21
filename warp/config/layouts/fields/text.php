<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/


echo '<p class="uk-form-controls-condensed">';

printf('<input %s>', $control->attributes(array_merge($node->attr(), array('type' => 'text', 'name' => $name, 'value' => $value)), array('label', 'description', 'default')));

if ($description = $node->attr('description')) {
    printf('<span class="uk-form-help-inline">%s</span>', $node->attr('description'));
}

echo '</p>';