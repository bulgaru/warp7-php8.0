<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

namespace Warp\Helper;

/**
 * Template helper class, render layouts.
 */
class TemplateHelper extends AbstractHelper
{
    /**
     * @var array
     */
    protected $slots = array();

    /**
     * Render a layout file.
     * 
     * @param string $resource
     * @param array  $args
     * 
     * @return string          
     */
    public function render($resource, $args = array())
    {
        // default namespace
        if (strpos($resource, ':') === false) {
            $resource = 'layouts:'.$resource;
        }

        // trigger event
        $this['event']->trigger('render.'.$resource, array(&$resource, &$args));

        // set resource and layout file
        $__resource = $resource;
        $__layout   = $this['path']->path($__resource.'.php');

        // render layout
        if ($__layout != false) {

            // import vars and get content
            extract($args);
            ob_start();
            include($__layout);
            return ob_get_clean();
        }

        trigger_error('<b>'.$__resource.'</b> not found in paths: ['.implode(', ', $this['path']->_paths['layouts']).']');

        return null;
    }

    /**
     * Slot exists ?
     * 
     * @param string $name
     * 
     * @return boolean      
     */
    public function has($name)
    {
        return isset($this->slots[$name]);
    }

    /**
     * Retrieve a slot.
     * 
     * @param string $name   
     * @param mixed  $default
     * 
     * @return mixed          
     */
    public function get($name, $default = false)
    {
        return isset($this->slots[$name]) ? $this->slots[$name] : $default;
    }

    /**
     * Set a slot.
     * 
     * @param string $name   
     * @param string $content
     */
    public function set($name, $content)
    {
        $this->slots[$name] = $content;
    }

    /**
     * Outputs slot content.
     * 
     * @param string $name   
     * @param mixed  $default
     * 
     * @return boolean
     */
    public function output($name, $default = false)
    {
        if (!isset($this->slots[$name])) {

            if (false !== $default) {
                echo $default;
                return true;
            }

            return false;
        }

        echo $this->slots[$name];
        return true;
    }
}
