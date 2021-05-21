<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

namespace Warp\Config\Loader;

/**
 * Loader chain.
 */
class LoaderChain implements LoaderInterface
{
    protected $loaders;

    public function __construct(array $loaders)
    {
        $this->loaders = $loaders;
    }

    public function load($filename)
    {
        return $this->getLoader($filename)->load($filename);
    }

    public function supports($filename)
    {
        return (bool) $this->getLoader($filename);
    }

    protected function getLoader($filename)
    {
        foreach ($this->loaders as $loader) {
            if ($loader->supports($filename)) {
                return $loader;
            }
        }

        return null;
    }
}
