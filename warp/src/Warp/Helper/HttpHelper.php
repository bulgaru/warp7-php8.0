<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

namespace Warp\Helper;

use Warp\Warp;
use Warp\Http\Client;

/**
 * HTTP client helper class.
 */
class HttpHelper extends AbstractHelper
{
    /**
     * HTTP client class.
     * 
     * @var string
     */
    protected $client;

    /**
     * Constructor.
     */
    public function __construct(Warp $warp)
    {
        parent::__construct($warp);

        $this->client = new Client;
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
        return $this->client->get($url, $options);
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
        return $this->client->post($url, $data, $options);
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
        return $this->client->put($url, $data, $options);
    }
}
