<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

$setDepths = function ($items) {
    foreach ($items as $i => $item) {
        if (!$item->menu_item_parent) {
            $item->level = 1;
            continue;
        }

        for ($j = $i; $j >= 0; $j--) {
            if ($items[$j]->ID == $item->menu_item_parent) {
                $item->level = $items[$j]->level + 1;
                break;
            }
        }
    }
}
?>

<p>Customize your menu appearance. To configure your menus, please visit the <a href="nav-menus.php">menu settings</a> screen.</p>

<div id="menus">

    <select data-menu-filter>
        <?php foreach ($menus = wp_get_nav_menus(array('hide_empty' => true, 'orderby' => 'name')) as $menu) : ?>
            <option value="<?php echo $menu->term_id ?>"><?php echo $menu->name ?></option>
        <?php endforeach ?>
    </select>

    <hr class="uk-article-divider">

    <?php foreach ($menus as $menu) : ?>
    <?php if ($items = wp_get_nav_menu_items($menu, array('post_status' => 'any'))) : ?>
    <?php $setDepths($items) ?>
    <table data-menu="<?php echo $menu->term_id ?>" class="uk-table uk-table-hover uk-table-middle tm-table">
        <thead>
            <tr>
                <th><?php echo $menu->name ?></th>
                <?php foreach ($node->children('field') as $field) : ?>
                <th data-uk-tooltip="{pos:'left'}" title="<?php echo $field->attr('tooltip');?>"><?php echo $field->attr('label') ?: $field->attr('column') ?></th>
                <?php endforeach ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item) : ?>
            <tr data-level="<?php echo $item->level ?>">
                <td><?php echo $item->title ?></td>
                <?php foreach ($node->find('field') as $field) : ?>
                <td>
                    <?php
                        if (!$field->attr('max-depth') || $field->attr('max-depth') >= $item->level) {
                            $fname = $field->attr('name');
                            $fvalue = $config->get("menus.{$item->ID}.{$fname}", $field->attr('default'));

                            echo $this['field']->render($field->attr('type'), "menus[{$item->ID}][{$fname}]", $fvalue, $field, compact('item'));
                        }
                     ?>
                </td>
                <?php endforeach ?>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
    <?php endif ?>
    <?php endforeach ?>
</div>
