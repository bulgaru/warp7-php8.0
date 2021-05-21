<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

namespace Warp\Asset\Filter;

/**
 * Asset filter collection.
 */
class FilterCollection implements FilterInterface, \Iterator
{
    /**
     * @var object
     */
    protected $filters;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->filters = new \SplObjectStorage();
    }

    /**
     * On load filter callback.
     * 
     * @param  object $asset
     */
    public function filterLoad($asset)
    {
        foreach ($this->filters as $filter) {
            $filter->filterLoad($asset);
        }
    }

    /**
     * On content filter callback.
     * 
     * @param  object $asset
     */
    public function filterContent($asset)
    {
        foreach ($this->filters as $filter) {
            $filter->filterContent($asset);
        }
    }

    /**
     * Add filter to collection.
     * 
     * @param object $filter
     */
    public function add($filter)
    {
        if ($filter instanceof Traversable) {
            foreach ($filter as $f) {
                $this->add($f);
            }
        } else {
            $this->filters->attach($filter);
        }
    }

    /**
     * Remove filter from collection.
     * 
     * @param  object $filter
     */
    public function remove($filter)
    {
        $this->filters->detach($filter);
    }

    /* Iterator interface implementation */

    public function current()
    {
        return $this->filters->current();
    }

    public function key()
    {
        return $this->filters->key();
    }

    public function valid()
    {
        return $this->filters->valid();
    }

    public function next()
    {
        $this->filters->next();
    }

    public function rewind()
    {
        $this->filters->rewind();
    }
}
