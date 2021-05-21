<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

namespace Warp\Config\Loader;

/**
 * Loader for .php files.
 */
class PhpLoader implements LoaderInterface
{
    public function load($filename)
    {
        $config = require $filename;
        $config = (1 === $config) ? array() : $config;
        return $config ?: array();
    }

    public function supports($filename)
    {
        return (bool) preg_match('#\.php(\.dist)?$#', $filename);
    }
}
