<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

namespace Warp\Helper;

/**
 * System check helper class.
 */
class CheckHelper extends AbstractHelper
{
    /**
     * @var array
     */
    protected $issues = array();

    /**
     * Retrieve issues by type (critical, notice).
     *
     * @param string $type
     *
     * @return array
     */
    public function getIssues($type = null)
    {
        return $type ? (isset($this->issues[$type]) ? $this->issues[$type] : array()) : $this->issues;
    }

    /**
     * Check if directory is writable.
     *
     * @param string $directory
     *
     * @return boolean
     */
    public function checkWritable($directory)
    {
        $writable = is_writable($directory);

        if (!$writable) {
            $this->issues['critical'][] = sprintf("Directory not writable: %s.", $this->relativePath($directory));
        }

        return $writable;
    }

    /**
     * Do all common checks.
     */
    public function checkCommon()
    {
        // check php version
        $current  = phpversion();
        $required = '5.3.3';

        if (version_compare($required, $current, '>')) {
           $this->issues['critical'][] = "<a href=\"http://php.net\">PHP</a> version {$current} is too old. Make sure to install {$required} or newer.";
        }

        // check json support
        if (!function_exists('json_decode')) {
           $this->issues['critical'][] = 'No <a href="http://php.net/manual/en/book.json.php">JSON</a> support available.';
        }

        // check dom xml support
        if (!class_exists('DOMDocument')) {
           $this->issues['critical'][] = 'No <a href="http://www.php.net/manual/en/book.dom.php">DOM XML</a> support available.';
        }

        // check multibyte string support
        if (!extension_loaded('mbstring')) {
           $this->issues['notice'][] = 'No <a href="http://php.net/manual/en/book.mbstring.php">Multibyte string (mbstring)</a> support available.';
        }
    }

    /**
     * Create relative path to system directory.
     *
     * @param string $path
     *
     * @return string
     */
    protected function relativePath($path)
    {
        return preg_replace('/'.preg_quote(str_replace(DIRECTORY_SEPARATOR, '/', $this['system']->path), '/').'/i', '', str_replace(DIRECTORY_SEPARATOR, '/', $path), 1).'/';
    }

    /**
     * Read files form a directory.
     *
     * @param string  $path
     * @param string  $prefix
     * @param boolean $filter
     * @param boolean $recursive
     *
     * @return array
     */
    protected function readDirectory($path, $prefix = '', $filter = false, $recursive = true)
    {
        $files  = array();
        $ignore = array('.', '..', '.DS_Store', '.svn', '.git', '.gitignore', '.gitmodules', 'cgi-bin');

        foreach (scandir($path) as $file) {

            // ignore file ?
            if (in_array($file, $ignore)) {
                continue;
            }

            // get files
            if (is_dir($path.'/'.$file) && $recursive) {
                $files = array_merge($files, $this->readDirectory($path.'/'.$file, $prefix.$file.'/', $filter, $recursive));
            } else {

                // filter file ?
                if ($filter && !preg_match($filter, $file)) {
                    continue;
                }

                $files[] = $prefix.$file;
            }
        }

        return $files;
    }
}
