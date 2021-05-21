<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

$column_order = $this['config']->get('multicolumns_order', 1);
$colcount = is_front_page() ? $this['config']->get('multicolumns', 1) : 1;
$count    = $this['system']->getPostCount();
$rows     = ceil($count / $colcount);
$columns  = array();
$row      = 0;
$column   = 0;
$i        = 0;

// create columns
while (have_posts()) {
    the_post();

    if ($column_order == 0) {
        // order down
        if ($row >= $rows) {
            $column++;
            $row  = 0;
            $rows = ceil(($count - $i) / ($colcount - $column));
        }
        $row++;
    } else {
        // order across
        $column = $i % $colcount;
    }

    if (!isset($columns[$column])) {
        $columns[$column] = '';
    }

    $columns[$column] .= $this->render('_post', array('is_column_item' => ($colcount > 1)));
    $i++;
}

// render columns
if ($count = count($columns)) {
    echo '<div class="uk-grid" data-uk-grid-match data-uk-grid-margin>';
    for ($i = 0; $i < $count; $i++) {
        echo '<div class="uk-width-medium-1-'.$count.'">'.$columns[$i].'</div>';
    }
    echo '</div>';
}
