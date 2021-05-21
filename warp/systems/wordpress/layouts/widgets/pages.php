<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

if ($ul = $this['dom']->create($widget->content)->first('ul:first')) {
    echo $ul->attr('class', 'uk-list uk-list-line')->html();
} else {
    echo $widget->content;
}
