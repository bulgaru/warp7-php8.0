<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

namespace Warp\Helper;

/**
 * Checksum helper class.
 */
class ChecksumHelper extends AbstractHelper
{
    /**
     * Create file checksums
     * 
     * @param string $path     
     * @param string $filename 
     * 
     * @return boolean
     */
    public function create($path, $filename = 'checksums')
    {
        $path  = rtrim(str_replace(DIRECTORY_SEPARATOR, '/', $path), '/').'/';
        $files = $this->readDirectory($path);

        if (is_array($files)) {
            $checksums = '';

            foreach ($files as $file) {

                // dont include the checksum file itself
                if ($file == $filename) {
                    continue;
                }

                $checksums .= md5_file($path.$file)." {$file}\n";
            }

            return file_put_contents($path.$filename, $checksums);
        }

        return false;
    }

    /**
     * Verify file checksums
     * 
     * @param string $path     
     * @param array  $log      
     * @param string $filename
     * 
     * @return boolean
     */
    public function verify($path, &$log, $filename = 'checksums')
    {
        $path = rtrim(str_replace(DIRECTORY_SEPARATOR, '/', $path), '/').'/';

        if ($rows = file($path.$filename)) {
            foreach ($rows as $row) {
                $parts = explode(' ', trim($row), 2);

                if (count($parts) == 2) {
                    list($md5, $file) = $parts;

                    if (!file_exists($path.$file)) {
                        $log['missing'][] = $file;
                    } elseif (md5_file($path.$file) != $md5) {
                        $log['modified'][] = $file;
                    }
                }
            }
        }

        return empty($log);
    }

    /**
     * Read files form a directory
     * 
     * @param string  $path      
     * @param string  $prefix    
     * @param boolean $recursive
     * 
     * @return array            
     */
    protected function readDirectory($path, $prefix = '', $recursive = true)
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
                $files = array_merge($files, $this->readDirectory($path.'/'.$file, $prefix.$file.'/', $recursive));
            } else {
                $files[] = $prefix.$file;
            }
        }

        return $files;
    }
}
