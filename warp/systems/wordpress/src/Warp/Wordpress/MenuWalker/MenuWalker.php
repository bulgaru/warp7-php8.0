<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

namespace Warp\Wordpress\MenuWalker;

/*
 *  Custom Menu Walker.
 */
class MenuWalker extends \Walker_Nav_Menu
{
    public function start_lvl(&$output, $depth = 0, $args = array())
    {
        $output .= '<ul>';
    }

    public function end_lvl(&$output, $depth = 0, $args = array())
    {
        $output .= '</ul>';
    }

    public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
    {
        // get warp
        global $warp;

        // init vars
        $data = array();
        $classes = empty($item->classes) ? array() : (array) $item->classes;

        // set id
        $data['data-id'] = $item->ID;

        // is current item ?
        if (in_array('current-menu-item', $classes) || in_array('current_page_item', $classes)) {
            $data['data-menu-active'] = 2;

        // home/fronpage item
        } elseif (preg_replace('/#(.+)$/', '', $item->url) == 'index.php' && (is_home() || is_front_page())) {
            $data['data-menu-active'] = 2;
        }

        // has columns ?
        if ($columns = (int) $warp['config']->get("menus.{$item->ID}.columns")) {
            $data['data-menu-columns'] = $columns;
        }

        // has columnwidth ?
        if ($columnwidth = (int) $warp['config']->get("menus.{$item->ID}.columnwidth")) {
            $data['data-menu-columnwidth'] = $columnwidth;
        }

        // has image ?
        if ($icon = $warp['config']->get("menus.{$item->ID}.icon")) {
            if (preg_match('/\.(gif|png|jpg|jpeg|svg)$/', $icon)) {
                $upload = wp_upload_dir();
                $data['data-menu-image'] = trailingslashit($upload['baseurl']).$icon;
            } else {
                $data['data-menu-icon'] = $icon;
            }
        }

        if ($subtitle = $warp['config']->get("menus.{$item->ID}.subtitle")) {
            $data['data-menu-subtitle'] = $subtitle;
        }

        // set item attributes
        $attributes = '';
        foreach ($data as $name => $value) {
            $attributes .= sprintf(' %s="%s"', $name, esc_attr($value));
        }

        // create item output
        $id = apply_filters('nav_menu_item_id', '', $item, $args);
        $output .= '<li'.(strlen($id) ? sprintf(' id="%s"', esc_attr($id)) : '').$attributes.'>';

        // set link attributes
        $attributes = '';
        foreach (array('attr_title' => 'title', 'target' => 'target', 'xfn' => 'rel', 'url' => 'href') as $var => $attr) {
            if (!empty($item->$var)) {
                $attributes .= sprintf(' %s="%s"', $attr, esc_attr($item->$var));
            }
        }

        $classes = trim(preg_replace('/menu-item(.+)/', '', implode(' ', $classes)));

        // is separator ?
        if ($item->url == '#') {
            $isline = preg_match("/^\s*\-+\s*$/", $item->title);

            $type = "header";
            if ($isline) {
                $type = 'separator-line';
            } elseif ($item->hasChildren) {
                $type = 'separator-text';
            }

            $format     = '%s<a href="#" %s>%s</a>%s';
            $classes   .= ' separator';

            $attributes = ' class="'.$classes.'" data-type="'.$type.'"';
        } else {
            $attributes .= ' class="'.$classes.'"';
            $format = '%s<a%s>%s</a>%s';
        }

        // create link output
        $item_output = sprintf($format, $args->before, $attributes, $args->link_before.apply_filters('the_title', $item->title, $item->ID).$args->link_after, $args->after);

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

    public function end_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
    {
        $output .= '</li>';
    }

    function display_element ($element, &$children_elements, $max_depth, $depth = 0, $args, &$output)
    {
        // attach to element so that it's available in start_el()
        $element->hasChildren = isset($children_elements[$element->ID]) && !empty($children_elements[$element->ID]);

        return parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
    }
}
