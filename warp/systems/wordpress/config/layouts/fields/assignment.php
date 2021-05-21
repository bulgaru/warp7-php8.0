<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

    $options  = array();
    $defaults = array(
        '*'          => 'All',
        'front_page' => 'Frontpage',
        'search'     => 'Search'
    );

    $selected = is_array($value) ? $value : array('*');

    if (count($selected) > 1 && in_array('*', $selected)) {
        $selected = array('*');
    }

    // set default options
    foreach ($defaults as $val => $label) {
        $attributes = in_array($val, $selected) ? array('value' => $val, 'selected' => 'selected') : array('value' => $val);
        $options[]  = sprintf('<option %s>%s</option>', $control->attributes($attributes), $label);
    }

    // set pages
    if ($pages = get_pages()) {
        $options[] = '<optgroup label="Pages">';

        array_unshift($pages, (object) array('post_title' => 'Pages (All)'));

        foreach ($pages as $page) {
            $val = isset($page->ID) ? 'page-'.$page->ID : 'page';
            $attributes = in_array($val, $selected) ? array('value' => $val, 'selected' => 'selected') : array('value' => $val);
            $options[]  = sprintf('<option %s>%s</option>', $control->attributes($attributes), $page->post_title);
        }

        $options[] = '</optgroup>';
    }

    // set posts
    $options[] = '<optgroup label="Post">';
    foreach (array('home', 'single', 'archive') as $view) {
        $val = $view;
        $attributes = in_array($val, $selected) ? array('value' => $val, 'selected' => 'selected') : array('value' => $val);
        $options[] = sprintf('<option %s>%s (%s)</option>', $control->attributes($attributes), 'Post', ucfirst($view));
    }
    $options[] = '</optgroup>';

    // set custom post types
    foreach (array_keys(get_post_types(array('_builtin' => false))) as $posttype) {
        $obj = get_post_type_object($posttype);
        $label = ucfirst($posttype);

        if ($obj->publicly_queryable) {
            $options[] = '<optgroup label="'.$label.'">';

            foreach (array('single', 'archive', 'search') as $view) {
                $val = $posttype.'-'.$view;
                $attributes = in_array($val, $selected) ? array('value' => $val, 'selected' => 'selected') : array('value' => $val);
                $options[] = sprintf('<option %s>%s (%s)</option>', $control->attributes($attributes), $label, ucfirst($view));
            }

            $options[] = '</optgroup>';
        }
    }

    // set categories
    foreach (array_keys(get_taxonomies()) as $tax) {

        if(in_array($tax, array("post_tag", "nav_menu"))) continue;

        if ($categories = get_categories(array( 'taxonomy' => $tax ))) {
            $options[] = '<optgroup label="Categories ('.ucfirst(str_replace(array("_","-")," ",$tax)).')">';

            foreach ($categories as $category) {
                $val        = 'cat-'.$category->cat_ID;
                $attributes = in_array($val, $selected) ? array('value' => $val, 'selected' => 'selected') : array('value' => $val);
                $options[]  = sprintf('<option %s>%s</option>', $control->attributes($attributes), $category->cat_name);
            }

            $options[] = '</optgroup>';
        }
    }

?>
<div class="uk-text-center">
    <div data-uk-dropdown="{mode:'click'}" class="uk-button-dropdown">
        <button type="button" class="uk-button uk-icon-bars"></button>
        <input type="hidden" name="<?php echo $name ?>[]" value="">
        <select class="uk-dropdown uk-dropdown-flip tm-assign-select" name="<?php echo $name ?>[]" multiple="multiple">
            <?php echo implode("", $options) ?>
        </select>
    </div>
</div>
