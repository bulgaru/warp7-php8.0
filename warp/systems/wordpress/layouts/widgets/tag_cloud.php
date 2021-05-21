<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

$taxonomy = (!empty($widget->params['taxonomy']) && taxonomy_exists($widget->params['taxonomy'])) ? $widget->params['taxonomy'] : 'post_tag';

$defaults = array(
        'smallest' => 8, 'largest' => 22, 'unit' => 'pt', 'number' => 45,
        'format' => 'flat', 'separator' => "\n", 'orderby' => 'name', 'order' => 'ASC',
        'exclude' => '', 'include' => '', 'link' => 'view', 'taxonomy' => $taxonomy, 'echo' => true
);

wp_tag_cloud($defaults);
