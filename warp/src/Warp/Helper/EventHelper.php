<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

namespace Warp\Helper;

/**
 * Event helper class to manage events.
 */
class EventHelper extends AbstractHelper
{
    /**
     * @var array
     */
    protected $events = array();

    /**
     * Bind a function to an event.
     * 
     * @param  string $event   
     * @param  mixed $callback
     */
    public function bind($event, $callback)
    {
        if (!isset($this->events[$event])) {
            $this->events[$event] = array();
        }

        $this->events[$event][] = $callback;
    }

    /**
     * Trigger Event
     * 
     * @param  string $event 
     * @param  array  $args  
     */
    public function trigger($event, $args = array())
    {
        if (isset($this->events[$event])) {
            foreach ($this->events[$event] as $callback) {
                $this->_call($callback, $args);
            }
        }
    }
}
