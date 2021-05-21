<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

namespace Warp\Menu;

/**
 * Nav menu renderer.
 */
class Nav
{
    /**
     * Process menu
     *
     * @param  object $module
     * @param  object $element
     * @return object
     */
    public function process($module, $element)
    {
        $ul = $element->first('ul:first')->attr('class', 'uk-nav');

        if ($module->nav_settings['accordion']) {

            $modifier = (isset($module->nav_settings["modifier"]) && $module->nav_settings["modifier"]) ? $module->nav_settings["modifier"] : 'uk-nav-side';

            $ul->addClass('uk-nav-parent-icon')->addClass($modifier)->attr('data-uk-nav', is_string($module->nav_settings['accordion']) ? $module->nav_settings['accordion']:'{}');

            foreach($ul->find("ul.level2") as $list) {

                if ($list->prev()->tag() == 'a') {

                    if (!$list->prev()->attr("href")) {
                        $list->prev()->attr("href", "#");
                    }

                    if ($list->prev()->attr("href") != "#" && $module->position == 'offcanvas') {
                        $list->parent()->addClass("uk-open");
                    }
                }

                $list->addClass("uk-nav-sub");
            }

        } else {

            foreach($ul->find("ul.level2") as $list) {
                $list->addClass("uk-nav-sub");
            }
        }

        return $element;
    }
}
