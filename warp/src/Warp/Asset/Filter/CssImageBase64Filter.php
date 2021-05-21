<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

namespace Warp\Asset\Filter;

/**
 * Replace stylesheets image urls with base64 image strings.
 */
class CssImageBase64Filter implements FilterInterface
{
    /**
     * On load filter callback.
     *
     * @param  object $asset
     */
    public function filterLoad($asset) {}

    /**
     * On content filter callback.
     *
     * @param object $asset
     */
    public function filterContent($asset)
    {
        $images  = array();
        $content = $asset->getContent();

        // get images and the related path
        if (preg_match_all('/url\(\s*[\'"]?([^\'"]+)[\'"]?\s*\)/Ui', $asset->getContent(), $matches)) {
            foreach ($matches[0] as $i => $url) {
                if ($path = realpath($asset['base_path'].'/'.ltrim(preg_replace('/'.preg_quote($asset['base_url'], '/').'/', '', $matches[1][$i], 1), '/'))) {
                    $images[$url] = isset($images[$url]) ? false : $path;
                }
            }
        }

        // check if image exists and filesize < 5kb
        foreach ($images as $url => $path) {
            if ($path && filesize($path) <= 5120 && preg_match('/\.(gif|png|jpg|svg)$/i', $path, $extension)) {
               $content = str_replace($url, sprintf('url(data:image/%s;base64,%s)', str_replace(array('jpg','svg'), array('jpeg','svg+xml'), strtolower($extension[1])), base64_encode(file_get_contents($path))), $content);
            }
        }

        $asset->setContent($content);
    }
}
