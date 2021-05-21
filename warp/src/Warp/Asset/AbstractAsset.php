<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

namespace Warp\Asset;

/**
 * Asset base class.
 */
abstract class AbstractAsset extends AssetOptions implements AssetInterface
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var boolean
     */
    protected $loaded = false;

    /**
     * Get asset type.
     *
     * @return string
     */
    public function getType() {
        return $this->type;
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
     * @param mixed $filter
     *
     * @return string
     */
    public function getContent($filter = null)
    {
        if (!$this->loaded) {
            $this->load($filter);
        }

        if ($filter) {
            $asset = clone $this;
            $filter->filterContent($asset);
            return $asset->getContent();
        }

        return $this->content;
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
     * Load asset and apply filters.
     *
     * @param string $content
     * @param object $filter
     */
    protected function doLoad($content, $filter = null)
    {
        $this->content = $content;

        if ($filter) {
            $filter->filterLoad($this);
        }

        $this->loaded = true;
    }
}
