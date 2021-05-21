<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

namespace Warp\Http\Transport;

/**
 * HTTP transport class using cURL.
 */
class CurlTransport extends AbstractTransport
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

        // set curl options
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, $request['version'] == '1.0' ? CURL_HTTP_VERSION_1_0 : CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $request['timeout']);
        curl_setopt($curl, CURLOPT_TIMEOUT, $request['timeout']);
        curl_setopt($curl, CURLOPT_MAXREDIRS, $request['redirects']);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        // post request ?
        if ($request['method'] == 'POST') {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $request['body']);
        }

        // put request ?
        if ($request['method'] == 'PUT') {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $request['method']);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $request['body']);
        }

        // connect with curl
        $res = curl_exec($curl);
        curl_close($curl);

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
        return function_exists('curl_init');
    }
}
