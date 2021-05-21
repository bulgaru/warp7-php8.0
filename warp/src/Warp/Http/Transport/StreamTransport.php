<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

namespace Warp\Http\Transport;

/**
 * HTTP transport class using fopen and streams.
 */
class StreamTransport extends AbstractTransport
{
    /**
     * Execute a HTTP request.
     * 
     * @param string $url
     * @param array  $options
     * 
     * @return mixed         
     */
    public function request($url, $options = array())
    {
        // parse request
        $request = $this->parseRequest($url, $options);

        // create stream options
        $options = array('http' =>
            array('method' => $request['method'],
                    'protocol_version' => $request['version'],
                  'max_redirects' => $request['redirects'],
                  'timeout' => $request['timeout'],
                  'ignore_errors' => true,
                  'content' => $request['body']
                )
            );

        // create header string
        $options['http']['header'] = $this->buildHeader($request['header']);
        if (!empty($request['cookies'])) {
            $options['http']['header'] .= $this->buildCookies($request['cookies']);
        }

        // connect with fopen and streams
        $res  = false;
        $fp   = @fopen($url, 'r', false, stream_context_create($options));
        $res  = stream_get_contents($fp);
        $meta = stream_get_meta_data($fp);
        fclose($fp);

        // parse response
        $res = $this->parseResponse((isset($meta['wrapper_data']) ? implode($this->line_break, $meta['wrapper_data']).$this->line_break.$this->line_break : null).$res);

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
        return function_exists('fopen') && function_exists('ini_get') && ini_get('allow_url_fopen') && !version_compare(PHP_VERSION, '5.0', '<');
    }
}
