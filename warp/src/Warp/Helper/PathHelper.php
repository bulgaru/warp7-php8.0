<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

namespace Warp\Helper;

use Warp\Warp;

/**
 * Path helper class, simplify path handling.
 */
class PathHelper extends AbstractHelper
{
    /**
     * @var array
     */
    public $_paths = array();

    /**
     * Constructor.
     */
    public function __construct(Warp $warp)
    {
        parent::__construct($warp);

        // register paths
        foreach ($this['config']->get('path', array()) as $namespace => $paths) {
            foreach ($paths as $path) {
                $this->register($path, $namespace);
            }
        }
    }

    /**
     * Register a path to a namespace.
     *
     * @param string $path
     * @param string $namespace
     */
    public function register($path, $namespace = 'default')
    {
        if (!isset($this->_paths[$namespace])) {
            $this->_paths[$namespace] = array();
        }

        array_unshift($this->_paths[$namespace], $path);

        return $this;
    }

    /**
     * Retrieve absolute path to a file or directory
     *
     * @param string $resource Resource with namespace, example: "assets:js/app.js"
     *
     * @return mixed
     */
    public function path($resource)
    {
        // parse resource
        extract($this->_parse($resource));

        return $this->_find($paths, $path);
    }

    /**
     * Retrieve absolute url to a file
     *
     * @param string  $resource Resource with namespace, example: "assets:js/app.js"
     * @param boolean $pathonly Wether return full url or just path
     *
     * @return mixed
     */
    public function url($resource, $pathonly = true)
    {
        // init vars
        $parts = explode('?', $resource);
        $url   = str_replace(DIRECTORY_SEPARATOR, '/', $this->path($parts[0]));
        $root  = $this['system']->url;

           // change root url to path only
        if ($pathonly) {
            $root = parse_url($root, PHP_URL_PATH);
        }

        if ($url) {

            if (isset($parts[1])) {
                $url .= '?'.$parts[1];
            }

            $url = $root.'/'.ltrim(preg_replace('/'.preg_quote(str_replace(DIRECTORY_SEPARATOR, '/', $this['system']->path), '/').'/i', '', $url, 1), '/');
        }

        return $url;
    }

    /**
     * Retrieve list of files from resource
     *
     * @param string  $resource
     * @param boolean $recursive
     *
     * @return array
     */
    public function files($resource, $recursive = false)
    {
        return $this->ls($resource, 'file', $recursive);
    }

    /**
     * Retrieve list of directories from resource
     *
     * @param string  $resource
     * @param boolean $recursive
     *
     * @return array
     */
    public function dirs($resource, $recursive = false)
    {
        return $this->ls($resource, 'dir', $recursive);
    }

    /**
     * Retrieve list of files or directories from resource
     *
     * @param string  $resource
     * @param string  $mode
     * @param boolean $recursive
     *
     * @return array
     */
    public function ls($resource, $mode = 'file', $recursive = false)
    {
        $files = array();
        $res   = $this->_parse($resource);

        foreach ($res['paths'] as $path) {
            foreach ($this->_list(realpath($path.'/'.$res['path']), '', $mode, $recursive) as $file) {
                if (!in_array($file, $files)) {
                    $files[] = $file;
                }
            }
        }

        return $files;
    }

    /**
     *  Parse resource string.
     *
     * @param string $resource
     *
     * @return string
     */
    protected function _parse($resource)
    {
        // init vars
        $parts     = explode(':', $resource, 2);
        $count     = count($parts);
        $path      = '';
        $namespace = 'default';

        // parse resource path
        if ($count == 1) {
            list($path) = $parts;
        } elseif ($count == 2) {
            list($namespace, $path) = $parts;
        }

        // remove heading slash or backslash
        $path = ltrim($path, "\\/");

        // get paths for namespace, if exists
        $paths = isset($this->_paths[$namespace]) ? $this->_paths[$namespace] : array();

        return compact('namespace', 'paths', 'path');
    }

    /**
     * Find file or directory in paths
     *
     * @param mixed $paths
     * @param string $file
     *
     * @return mixed
     */
    protected function _find($paths, $file)
    {
        $paths = (array) $paths;
        $file  = ltrim($file, "\\/");

        foreach ($paths as $path) {

            $fullpath = realpath("$path/$file");
            $path     = realpath($path);

            if (file_exists($fullpath) && substr($fullpath, 0, strlen($path)) == $path) {
                return $fullpath;
            }
        }

        return false;
    }

    /**
     * List files or directories in a path
     *
     * @param string  $path
     * @param string  $prefix    Prefix prepended to every file/directory
     * @param string  $mode      Mode 'file' or 'dir'
     * @param boolean $recursive
     *
     * @return array
     */
    protected function _list($path, $prefix = '', $mode = 'file', $recursive = false)
    {
        $files  = array();
        $ignore = array('.', '..', '.DS_Store', '.svn', 'cgi-bin');

        if (is_readable($path) && is_dir($path) && ($scan = scandir($path))) {
            foreach ($scan as $file) {

                // continue if ignore match
                if (in_array($file, $ignore)) {
                    continue;
                }

                if (is_dir($path.'/'.$file)) {

                    // add dir
                    if ($mode == 'dir') {
                        $files[] = $prefix.$file;
                    }

                    // continue if not recursive
                    if (!$recursive) {
                        continue;
                    }

                    // read subdirectory
                    $files = array_merge($files, $this->_list($path.'/'.$file, $prefix.$file.'/', $mode, $recursive));

                } else {

                    // add file
                    if ($mode == 'file') {
                        $files[] = $prefix.$file;
                    }

                }

            }
        }

        return $files;
    }
}
