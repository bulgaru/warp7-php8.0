<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

namespace Warp\Asset;

/**
 * Asset collection.
 */
class AssetCollection extends AssetOptions implements AssetInterface, \IteratorAggregate
{
    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var object
     */
    protected $assets;

    /**
     * Constructor.
     *
     * @param array $assets
     * @param array $options
     */
    public function __construct($assets = array(), $options = array())
    {
        parent::__construct($options);

        if (!is_array($assets)) {
            $assets = array($assets);
        }

        $this->assets = $assets;
    }

    /**
     * Get asset url.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set asset url.
     *
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Get asset content and apply filters.
     *
     * @param object $filter
     *
     * @return string
     */
    public function getContent($filter = null)
    {
        $content = array();

        foreach ($this as $asset) {
            $content[] = $asset->getContent($filter);
        }

        return implode("\n", $content);
    }

    /**
     * Set asset content.
     *
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Load asset callback.
     *
     * @param object $filter
     */
    public function load($filter = null)
    {
        $content = array();

        foreach ($this as $asset) {
            $content[] = $asset->getContent($filter);
        }

        $this->content = implode("\n", $content);
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
        $hashes = array();

        foreach ($this as $asset) {
            $hashes[] = $asset->hash($salt);
        }

        return md5(implode(' ', $hashes));
    }

    /**
     * Add asset to collection.
     *
     * @param AssetInterface $asset
     */
    public function add(AssetInterface $asset)
    {
        $this->assets[] = $asset;
    }

    /**
     * Prepend asset to collection.
     *
     * @param AssetInterface $asset
     */
    public function prepend(AssetInterface $asset)
    {
        array_unshift($this->assets, $asset);
    }

    /**
     * Remove asset from collection.
     *
     * @param object $asset
     */
    public function remove($asset)
    {
        $key = array_search($asset, $this->assets);

        if ($key !== false) {
            unset($this->assets[$key]);
        }
    }

    /**
     * Replace asset from collection.
     *
     * @param object $search
     * @param object $replace
     */
    public function replace($search, $replace)
    {
        $key = array_search($search, $this->assets);

        if ($key !== false) {
            $this->assets[$key] = $replace;
        }
    }

    /**
     * Iterator aggregate interface implementation.
     *
     * @return Iterator
     */
    public function getIterator() {
        return new \ArrayIterator($this->assets);
    }
}
