<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

namespace Warp\Wordpress\Helper;

use Warp\Helper\AbstractHelper;
use Warp\Warp;

/**
 * Option helper class, store option data.
 */
class OptionHelper extends AbstractHelper
{
    /**
     * Option prefix
     *
     * @var string
     */
    protected $prefix;

    /**
     * Class constructor.
     *
     * @param Warp $warp
     */
    public function __construct(Warp $warp)
    {
        parent::__construct($warp);

        // set prefix
        $this->prefix = basename($this['path']->path('theme:'));
    }

    /**
     * Get a value from data
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function get($name, $default = null)
    {
        return get_option($this->prefix.$name, $default);
    }

    /**
     * Set a value
     *
     * @param string $name
     * @param mixed $value
     */
    public function set($name, $value)
    {
        update_option($this->prefix.$name, $value);
    }
}
