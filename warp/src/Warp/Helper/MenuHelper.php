<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

namespace Warp\Helper;

use Warp\Warp;

/**
 * Menu helper class.
 */
class MenuHelper extends AbstractHelper
{
    /**
     * Menu renderers.
     *
     * @var array
     */
    protected $renderers = array();

    /**
     * Constructor.
     */
    public function __construct(Warp $warp)
    {
        parent::__construct($warp);

        // register menus
        foreach ($this['config']->get('menu', array()) as $name => $renderer) {
            $this->register($name, $renderer);
        }
    }

    /**
     * Register a menu renderer.
     *
     * @param string          $name
     * @param string|Renderer $renderer
     */
    public function register($name, $renderer)
    {
        $this->renderers[$name] = $renderer;
    }

    /**
     * Process menu module and apply renderers.
     *
     * @param object $module
     * @param array  $renderers
     *
     * @return string
     */
    public function process($module, $renderers)
    {
        // init vars
        $menu     = $this['dom']->create($module->content);
        $defaults = array('modifier' => '', 'accordion' => false);

        $module->nav_settings = isset($module->nav_settings) ? array_merge($defaults, $module->nav_settings) : $defaults;

        foreach (array_unique((array) $renderers) as $renderer) {

            if (isset($this->renderers[$renderer])) {

                if (is_string($class = $this->renderers[$renderer])) {
                    $this->renderers[$renderer] = new $class;
                }

                $menu = $this->renderers[$renderer]->process($module, $menu);
            }

            if (!$menu) {
                return $module->content;
            }
        }

        return $menu->first('ul:first')->html();
    }
}
