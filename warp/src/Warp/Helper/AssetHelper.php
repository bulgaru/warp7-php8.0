<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

namespace Warp\Helper;

use Warp\Warp;
use Warp\Asset\AssetCollection;
use Warp\Asset\FileAsset;
use Warp\Asset\StringAsset;

/**
 * Asset helper class to manage assets.
 */
class AssetHelper extends AbstractHelper
{
    /**
     * @var array
     */
    protected $assets;

    /**
     * @var array
     */
    protected $options;

    /**
     * Constructor
     */
    public function __construct(Warp $warp)
    {
        parent::__construct($warp);

        // init vars
        $this->assets  = array();
        $this->options = array('base_path' => $this['system']->path, 'base_url' => rtrim($this['path']->url('site:'), '/'));
    }

    /**
     * Get a asset collection
     *
     * @param  string $name
     * @return mixed
     */
    public function get($name)
    {
        return isset($this->assets[$name]) ? $this->assets[$name] : null;
    }

    /**
     * Create a string asset
     *
     * @param  string $input
     * @param  array  $options
     * @return object
     */
    public function createString($input, $options = array())
    {
        return new StringAsset($input, array_merge($options, $this->options));
    }

    /**
     * Create a file asset
     *
     * @param  string $input
     * @param  array  $options
     * @return object
     */
    public function createFile($input, $options = array())
    {
        $url  = $input;
        $path = null;

        if (!preg_match('/^(http|https)\:\/\//i', $input)) {

            // resource identifier ?
            if ($path = $this['path']->path($input)) {
                $url = $this['path']->url($input);
            }

            // absolute/relative path ?
            if (!$path) {
                $path = realpath($this->options['base_path'].'/'.ltrim(preg_replace('/'.preg_quote(str_replace(DIRECTORY_SEPARATOR, '/', $this->options['base_url']), '/').'/', '', $input, 1), '/'));
            }

        }

        return new FileAsset($url, $path, array_merge($options, $this->options));
    }

    /**
     * Add a string asset
     *
     * @param string $name
     * @param string $input
     * @param array  $options
     * @return mixed
     */
    public function addString($name, $input, $options = array())
    {
        return $this->addAsset($name, $this->createString($input, $options));
    }


    /**
     * Add a file asset
     *
     * @param string $name
     * @param string $input
     * @param array  $options
     * @return mixed
     */
    public function addFile($name, $input, $options = array())
    {
        return $this->addAsset($name, $this->createFile($input, $options));
    }

    /**
     * Add asset object
     *
     * @param string $name
     * @param object $asset
     */
    protected function addAsset($name, $asset)
    {
        if (!isset($this->assets[$name])) {
            $this->assets[$name] = new AssetCollection();
        }

        $this->assets[$name]->add($asset);

        return $asset;
    }

    /**
     * Apply filters and cache a asset
     *
     * @param  string $file
     * @param  object $asset
     * @param  array  $filters
     * @param  array  $options
     * @return object
     */
    public function cache($file, $asset, $filters = array(), $options = array())
    {
        // init vars
        $hash = substr($asset->hash(serialize($filters)), 0, 8);
        $options = array_merge(array('Gzip' => false), $options);

        // copy gzip file, if not exists
        if ($options['Gzip'] && !$this['path']->path('cache:gzip.php')) {
            @copy($this['path']->path('warp:gzip/gzip.php'), rtrim($this['path']->path('cache:'), '/').'/gzip.php');
        }

        // append cache file suffix based on hash
        if ($extension = pathinfo($file, PATHINFO_EXTENSION)) {
            $file = preg_replace('/'.preg_quote('.'.$extension, '/').'$/', sprintf('-%s.%s', $hash, $extension), $file, 1);
        } else {
            $file .= '-'.$hash;
        }

        // create cache file, if not exists
        if (!$this['path']->path('cache:'.$file)) {

            $content = $asset->getContent($this['assetfilter']->create($filters));

            // move unresolved @import rules to the top
            if (in_array('CssImportResolver', $filters)) {
                $regexp = '/@import[^;]+;/i';
                if (preg_match_all($regexp, $content, $matches)) {
                    $content = preg_replace($regexp, '', $content);
                    $content = implode("\n", $matches[0])."\n".$content;
                }
            }

            @file_put_contents($this['path']->path('cache:').'/'.ltrim($file, '/'), $content);
        }

        $asset->setUrl($this['path']->url(($options['Gzip'] && $this['path']->path('cache:gzip.php') ? 'cache:gzip.php?' : 'cache:').$file));

        return $asset;
    }
}
