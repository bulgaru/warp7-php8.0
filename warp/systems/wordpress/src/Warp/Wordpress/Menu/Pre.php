<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

namespace Warp\Wordpress\Menu;

/*
 * Menu base class
 */
class Pre
{
    /**
     * {@inheritdoc}
     */
    public function process($module, $element)
    {
        // has ul ?
        if (!$element->first('ul:first')) {
            return false;
        }

        // remove id
        $element->first('ul:first')->removeAttr('id');

        // set active
        foreach ($element->find('li') as $li) {
            if ($li->attr('data-menu-active') == 2) {

                $ele = $li;

                while (($ele = $ele->parent()) && $ele->nodeType == XML_ELEMENT_NODE) {
                    if ($ele->tag() == 'li' && !$ele->attr('data-menu-active')) {
                        $ele->attr('data-menu-active', 1);
                    }
                }
            }
        }

        return $element;
    }
}
