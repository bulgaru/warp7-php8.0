<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

// layout options
$layouts = $config->get('layouts', array('default' => array()));

// pages & section options

$options  = array();
$defaults = array(
    'front_page' => 'Frontpage',
    'home'       => 'Home (Posts page)',
    'archive'    => 'Archive',
    'search'     => 'Search',
    'single'     => 'Single',
    'page'      => 'Pages',
);

foreach (array_keys(get_post_types()) as $posttype) {
    if (!in_array($posttype, array("post", "page", "attachment", "revision", "nav_menu_item"))) {
        $defaults[$posttype] = ucfirst(str_replace(array("_", "-"), " ", $posttype));
    }
}

$options['Posttypes'] = $defaults;

// set pages
if ($pages = get_pages()) {
    foreach ($pages as $page) {
        $options['Pages']['page-'.$page->ID]  = $page->post_title;
    }
}

// set categories
foreach (array_keys(get_taxonomies()) as $tax) {

    if(in_array($tax, array("post_tag", "nav_menu"))) continue;

    if ($categories = get_categories(array( 'taxonomy' => $tax ))) {
        $key = 'Categories ('.ucfirst(str_replace(array("_","-")," ",$tax)).')';
        foreach ($categories as $category) {
            $options[$key]['cat-'.$category->cat_ID]  = $category->cat_name;
        }
    }
}

?>

<div id="layout" data-field-name="<?php echo $name ?>">

    <p>
        Store your modifications in a layout profile and assign it to different pages. The <em>default</em> layout will be used on pages without an assigned layout.
    </p>

    <p>

        <select data-layout-selector class="uk-form-width-small">
            <?php foreach (array_keys($layouts) as $layout) : ?>
                <option value="<?php echo $layout ?>"><?php echo $layout ?></option>
            <?php endforeach ?>
        </select>

        <a data-action="add" class="uk-button" href="#">Add</a>
        <a data-action="rename" class="uk-button" href="#">Rename</a>
        <a data-action="remove" class="uk-button" href="#">Remove</a>

    </p>

    <?php foreach ($layouts as $layout => $values) : ?>
    <div data-layout="<?php echo $layout ?>">

        <?php echo $this->render('config:layouts/fields', array('config' => $config, 'fields' => $node, 'values' => $values, 'prefix' => "{$name}[{$layout}]", 'attr' => array('data-layout' => $layout))) ?>

        <hr data-assignment class="uk-article-divider">

        <h2 data-assignment>Assignment</h2>

        <p data-assignment>Assign this layout to specific pages.</p>

        <div data-assignment class="uk-scrollable-box uk-margin-top tm-width tm-scrollable-box">
            <?php foreach ($options as $title => $option) : ?>
            <h2><?php echo $title ?></h2>
            <ul class="uk-list">
            <?php foreach ($option as $value => $text) : ?>
                <li>
                    <label>
                        <input value="<?php echo $value ?>" name="<?php echo "{$name}[{$layout}][assignment][]" ?>" type="checkbox"<?php if (is_array($layouts[$layout]['assignment']) && in_array($value, $layouts[$layout]['assignment'])) echo ' checked="checked"' ?>> <?php echo $text ?>
                    </label>
                <?php endforeach ?>
                </ul>
            <?php endforeach ?>
        </div>
    </div>
    <?php endforeach ?>
</div>
