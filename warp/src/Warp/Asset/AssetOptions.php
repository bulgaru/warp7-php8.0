<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

namespace Warp\Asset;

/**
 * Asset options class, provides options implementation.
 */
abstract class AssetOptions implements \ArrayAccess
{
    /**
     * @var array
     */
    protected $options;

    /**
     * Constructor.
     *
     * @param array $options
     */
    public function __construct($options = array())
    {
        $this->options = $options;
    }

    /**
     * Get options.
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /* ArrayAccess interface implementation */

    public function offsetSet($name, $value)
    {
        $this->options[$name] = $value;
    }

    public function offsetGet($name)
    {
        return isset($this->options[$name]) ? $this->options[$name] : null;
    }

    public function offsetExists($name)
    {
        return isset($this->options[$name]);
    }

    public function offsetUnset($name)
    {
        unset($this->options[$name]);
    }
}
