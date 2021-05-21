<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

namespace Warp\Http;

/**
 * HTTP client class.
 */
class Client
{
    /**
     * Current transport class.
     * 
     * @var string
     */
    protected $transport;

    /**
     * Available transport classes.
     * 
     * @var array
     */
    protected $transports = array(
        'Warp\Http\Transport\CurlTransport',
        'Warp\Http\Transport\StreamTransport',
        'Warp\Http\Transport\SocketTransport'
    );

    /**
     * Constructor.
     */
    public function __construct()
    {
        // check available library support
        foreach ($this->transports as $classname) {
            $transport = new $classname;
            if ($transport->available()) {
                $this->transport = $transport;
                break;
            }
        }
    }

    /**
     * Execute a GET HTTP request.
     * 
     * @param string $url    
     * @param array  $options
     * 
     * @return mixed         
     */
    public function get($url, $options = array())
    {
        return $this->request($url, $options);
    }

    /**
     * Execute a POST HTTP request.
     * 
     * @param string $url    
     * @param string $data
     * @param array  $options
     * 
     * @return mixed         
     */
    public function post($url, $data = null, $options = array())
    {
        return $this->request($url, array_merge(array('method' => 'POST', 'body' => $data), $options));
    }

    /**
     * Execute a PUT HTTP request.
     * 
     * @param string $url
     * @param string $data
     * @param array  $options
     * 
     * @return mixed         
     */
    public function put($url, $data = null, $options = array())
    {
        return $this->request($url, array_merge(array('method' => 'PUT', 'body' => $data), $options));
    }

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
        if ($this->transport) {
            return $this->transport->request($url, $options);
        }

        return false;
    }
}
