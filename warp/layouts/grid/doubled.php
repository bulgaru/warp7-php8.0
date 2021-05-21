<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

$count = count($widgets);

$widths = array(
    array('1-1'),
    array('2-3', '1-3'),
    array('2-4', '1-4', '1-4'),
    array('2-5', '1-5', '1-5', '1-5'),
    array('2-6', '1-6', '1-6', '1-6', '1-6'),
    array('1-6', '1-6', '1-6', '1-6', '1-6', '1-6')
);

foreach ($widgets as $index => $widget) {
    $classes = array();
    $prev = 0;
    foreach ($displays as $class => &$display) {
        if (false !== $pos = array_search($index, $display)) {

            if ($count > 6) {
                $classes[] = 'uk-flex-item-1 uk-width-small-1-1';
                continue;
            }

            $width = in_array($class, $stacked) ? '1-1' : $widths[count($display)-1][$pos];
            if ($width != $prev) {
                $classes[] = "uk-width".($class != 'small' ? "-{$class}" : '')."-{$width}";
                $prev = $width;
            }
        } else {
            $classes[] = "uk-hidden-{$class}";
        }
    }

    printf(PHP_EOL.'<div class="%s">%s</div>'.PHP_EOL, implode(' ', $classes), $widget);
}
