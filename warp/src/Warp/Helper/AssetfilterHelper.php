<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

namespace Warp\Helper;

use Warp\Asset\Filter\FilterCollection;

/**
 * Asset filter helper class to filter assets.
 */
class AssetfilterHelper extends AbstractHelper
{
    /**
     * @var string
     */
    protected $namespace = 'Warp\Asset\Filter\%sFilter';

    /**
     * Create filter instances.
     * 
     * @param string|array $filters
     * 
     * @return FilterInterface
     */
    public function create($filters = array())
    {
        // one filter
        if (is_string($filters)) {
            $class = sprintf($this->namespace, $filters);
            return new $class;
        }

        // multiple filter
        $collection = new FilterCollection;

        foreach ($filters as $name) {
            $class = sprintf($this->namespace, $name);
            $collection->add(new $class);
        }

        return $collection;
    }
}
