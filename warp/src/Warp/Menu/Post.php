<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

namespace Warp\Menu;

/**
 * Post menu renderer.
 */
class Post
{
    protected $scrollspy = false;
    protected $active_url = '';

    /**
     * Process menu
     *
     * @param  object $module
     * @param  object $element
     * @return object
     */
    public function process($module, $element)
    {

        $use_scrollspy  = false;
        $active_url     = false;

        //assuming the first active menu item is the current page
        if ($module->nav_settings['scrollspy'] && $active = $element->first('li.uk-active')) {
            $active     = $active->first('a');
            $active_url = preg_replace('/#(.+)$/', '', $active->attr('href'));
        }

        foreach ($element->find('a') as $ele) {

            // check if scrollspy needs to be applied
            if ($module->nav_settings['scrollspy'] && $active_url && strpos($ele->attr('href'), $active_url.'#') !== false) {
                $use_scrollspy = true;

                if (strpos($active->attr('href'), '#') === false) {
                    $active->attr('href', '#top');
                }
                $ele->attr('href', strstr($ele->attr('href'), '#'));
            }

            if ($type = $ele->attr("data-type")) {

                if ($type=="separator-line") {

                    $ele->parent()->addClass("uk-nav-divider");
                    $ele->parent()->removeChild($ele);

                } else if ($type=="separator-text") {

                    $ele->removeAttr('data-type');

                } else { // header

                    $ele->removeAttr('data-type');

                    if (!$ele->parent()->parent()->hasClass('uk-navbar-nav') && !($module->nav_settings["accordion"] && $ele->parent()->first("ul.level2"))) {

                        $ele->parent()->addClass("uk-nav-header");

                        foreach ($ele->children() as $child) {
                            $ele->parent()->warp_prepend($child);
                        }

                        $ele->warp_replaceWith($ele->text());
                    }

                }
            }
        }

        foreach($element->first("ul:first")->addClass($module->nav_settings["modifier"])->find('ul.level2 ul') as $ul) {
            if (!$ul->hasClass('uk-nav-sub')) $ul->removeAttr("class");
        }

        foreach ($element->find('li') as $li) {
            $li->removeAttr('data-id')->removeAttr('data-menu-active')->removeAttr('data-menu-columns')->removeAttr('data-menu-columnwidth')->removeAttr('data-menu-icon')->removeAttr('data-menu-image')->removeAttr('data-menu-subtitle');
            $li->removeClass("level1")
               ->removeClass("level2")
               ->removeClass("level3")
               ->removeClass("level4")
            ->parent()
               ->removeClass("level1")
               ->removeClass("level2")
               ->removeClass("level3")
               ->removeClass("level4");
        }

        // apply scrollspy
        if ($use_scrollspy && $active_url) {
            $element->first('ul:first')->attr('data-uk-scrollspy-nav', '{closest: \'li\', smoothscroll: true}');
        }

        return $element;
    }
}
