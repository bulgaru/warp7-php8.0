<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

namespace Warp\Http\Transport;

/**
 * HTTP transport class using fsockopen.
 */
class SocketTransport extends AbstractTransport
{
    /**
     * Execute a HTTP request
     * 
     * @param  string $url    
     * @param  array  $options
     * @return mixed         
     */
    public function request($url, $options = array())
    {
        // parse request
        $request = $this->parseRequest($url, $options);

        // set host
        $host = $request['url']['scheme'] == 'https' ? sprintf('ssl://%s', $request['url']['host']) : $request['url']['host'];

        // connect with fsockopen
        $res = false;
        $fp  = @fsockopen($host, $request['url']['port'], $errno, $errstr, $request['url']['timeout']);
        if ($fp !== false) {
            @fwrite($fp, $request['raw']);
            while (!feof($fp)) {
                $res .= fgets($fp, 4096);
            }
            @fclose($fp);
        }

        // parse response
        $res = $this->parseResponse($res);

        // save to file
        if ($res && $request['file'] && file_put_contents($request['file'], $res['body']) === false) {
            return false;
        }

        return $res;
    }

    /**
     * Check if HTTP request method is available.
     * 
     * @return boolean
     */
    public function available()
    {
        return function_exists('fsockopen');
    }
}
