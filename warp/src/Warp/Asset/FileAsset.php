<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

namespace Warp\Asset;

/**
 * File asset.
 */
class FileAsset extends AbstractAsset
{
    /**
     * @var string
     */
    protected $path;

    /**
     * Constructor.
     *
     * @param string $url
     * @param string $path
     * @param array  $options
     */
    public function __construct($url, $path, $options = array())
    {
        parent::__construct($options);

        $this->type = 'File';
        $this->url = $url;
        $this->path = $path;
    }

    /**
     * Get asset file path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Load asset callback.
     *
     * @param object $filter
     */
    public function load($filter = null)
    {
        if (file_exists($this->path)) {
            $this->doLoad(preg_replace('{^\xEF\xBB\xBF|\x1A}', '', file_get_contents($this->path)), $filter); // load with UTF-8 BOM removal
        }
    }

    /**
     * Get unique asset hash.
     *
     * @param string $salt
     *
     * @return string
     */
    public function hash($salt = '')
    {
        return md5($this->path.filemtime($this->path).$salt);
    }
}
