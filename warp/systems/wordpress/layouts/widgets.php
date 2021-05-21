<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

//render widgets
$widgets    = $this['widgets']->load($position);
$count      = count($widgets);
$output     = array();
$config     = $this['config'];
$displays   = array_fill_keys(array('small', 'medium', 'large'), array());
$responsive = $config->get("grid.{$position}.responsive", '') ?: 'small';
$stacked    = array_diff($keys = array_keys($displays), array_slice($keys, array_search($responsive, $keys)));
foreach ($widgets as $index => $widget) {

    // set params
    $params           = $config->get("widgets.{$widget->id}", array());
    $params['count']  = $count;
    $params['order']  = $index + 1;
    $params['first']  = $params['order'] == 1;
    $params['last']   = $params['order'] == $count;
    $params['suffix'] = $widget->suffix;

    // pass through menu params
	if ($widget->menu) {
		$widget->nav_settings = array('scrollspy' => false);
	}

    // set position params
    $widget->position_params = $params;

    // core overrides
    if (in_array($widget->type, array('search', 'links', 'categories', 'pages', 'archives', 'recent-posts', 'recent-comments', 'calendar', 'meta', 'rss', 'tag_cloud', 'text'))) {
        $widget->content = $this->render('widgets/'.$widget->type, compact('widget'));
    }

    // render module
    $output[] = $this->render('widget', compact('widget', 'params'));

    foreach ($displays as $name => &$display) {
        if ($config->get("widgets.{$widget->id}.display.{$name}", true)) {
            $display[] = $index;
        }
    }
}

// render module layout
echo (isset($layout) && $layout) ? $this->render("grid/{$layout}", array('widgets' => $output, 'displays' => $displays, 'stacked' => $stacked)) : implode("\n", $output);
