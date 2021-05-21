<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

// get widgets
$widgets = $this['widgets']->getWidgets();

// get position settings
$positions = array();
foreach ($this['system']->xml->find('positions > position') as $position) {
    $posname = $position->text();
    if (isset($widgets[$posname]) and (!$position->hasAttribute('settings') or $position->attr('settings'))) {
        $positions[$posname] = $position;
    }
}

?>

<div id="widgets">

    <p>Customize your widgets appearance and select your favorite style, icon or badge. To configure your widgets, please visit the <a href="widgets.php">widgets settings</a> screen.</p>

    <input type="text" placeholder="Search" data-widget-filter>
    <select data-position-filter>
        <option value="">All</option>
        <?php foreach ($positions as $posname => $position) : ?>
            <option value="<?php echo $posname ?>"><?php echo $posname ?></option>
        <?php endforeach ?>
    </select>

    <hr class="uk-article-divider">

    <?php foreach ($positions as $posname => $position) : ?>

        <table data-position="<?php echo $posname ?>" class="uk-table uk-table-hover uk-table-middle tm-table">
            <thead>
                <tr>
                    <th><?php echo $posname ?></th>

                    <?php foreach ($node->children('field') as $field) : ?>
                    <?php if (!$position->hasAttribute('settings') or in_array($field->attr('name'), explode(' ', $position->attr('settings')))) : ?>
                    <th><?php echo $field->attr('label') ?: $field->attr('column') ?></th>
                    <?php endif ?>
                    <?php endforeach  ?>
                </tr>
            </thead>
            <tbody>
            <?php

                $html = array();
                foreach ($widgets[$posname] as $widget) {

                    $html[] = '<tr data-widget-name="'.(isset($widget->params['title']) && $widget->params['title'] ? $widget->params['title'] : $widget->name).'">';
                    $html[] = '<td><div class="uk-text-truncate">'.(isset($widget->params['title']) && $widget->params['title'] ? $widget->params['title'] : $widget->name).'</div></td>';

                    foreach ($node->children('field') as $field) {

                        $fname  = $field->attr('name');
                        $value = $config->get("widgets.{$widget->id}.{$fname}", $field->attr('default'));

                        if (!$position->hasAttribute('settings') or in_array($field->attr('name'), explode(' ', $position->attr('settings')))) {
                            $html[] = '<td>';
                            $html[] = $this['field']->render($field->attr('type'), "{$name}[{$widget->id}][{$fname}]", $value, $field, compact('widget'));
                            $html[] = '</td>';
                        }

                    }

                    $html[] = '</tr>';
                }

                echo implode("\n", $html);
            ?>
            </tbody>
        </table>
    <?php endforeach ?>

</div>
