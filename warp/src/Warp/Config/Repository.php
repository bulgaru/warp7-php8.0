<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

namespace Warp\Config;

use Warp\Config\Loader\PhpLoader;
use Warp\Config\Loader\JsonLoader;
use Warp\Config\Loader\LoaderChain;
use Warp\Config\Loader\LoaderInterface;

class Repository implements \ArrayAccess
{
    /**
     * @var array
     */
    protected $files = array();

    /**
     * @var array
     */
    protected $values = array();

    /**
     * @var array
     */
    protected $replacements = array();

    /**
     * @var LoaderInterface
     */
    protected $loader;

    /**
     * Create a new configuration repository.
     *
     * @param array           $replacements
     * @param LoaderInterface $loader
     */
    public function __construct($replacements = array(), LoaderInterface $loader = null)
    {
        $this->replacements = $replacements;
        $this->loader = $loader ?: new LoaderChain(array(new PhpLoader, new JsonLoader));
    }

    /**
     * Returns the all replacements.
     *
     * @return array
     */
    public function getReplacements()
    {
        return $this->replacements;
    }

    /**
     * Add a replacement.
     *
     * @param string $key
     * @param mixed  $value
     */
    public function addReplacement($key, $value)
    {
        $this->replacements[$key] = $value;
    }

    /**
     * Get all configuration values.
     *
     * @return array
     */
    public function getValues()
    {
        $this->doLoad();

        return $this->values;
    }

    /**
     * Set configuration values.
     */
    public function setValues(array $values)
    {
        $this->doLoad();

        $this->values = $this->merge($this->values, $values);
    }

    /**
     * Determine if the given configuration value exists.
     *
     * @param string $key
     *
     * @return bool
     */
    public function has($key)
    {
        $default = microtime(true);

        return $this->get($key, $default) != $default;
    }

    /**
     * Get the specified configuration value.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $this->doLoad();

        $array = $this->values;

        if (isset($array[$key])) {
            return $array[$key];
        }

        foreach (explode('.', $key) as $segment) {

            if (!is_array($array) || !array_key_exists($segment, $array)) {
                return $default;
            }

            $array = $array[$segment];
        }

        return $array;
    }

    /**
     * Set a given configuration value.
     *
     * @param string $key
     * @param mixed  $value
     */
    public function set($key, $value)
    {
        $this->doLoad();

        $keys = explode('.', $key);
        $array =& $this->values;

        while (count($keys) > 1) {

            $key = array_shift($keys);

            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = array();
            }

            $array =& $array[$key];
        }

        $array[array_shift($keys)] = $value;
    }

    /**
     * Determine if the given configuration option exists.
     *
     * @param string $key
     *
     * @return bool
     */
    public function offsetExists($key)
    {
        return $this->has($key);
    }

    /**
     * Get a configuration option.
     *
     * @param string $key
     *
     * @return bool
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }

    /**
     * Set a configuration option.
     *
     * @param string $key
     * @param string $value
     */
    public function offsetSet($key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * Unset a configuration option.
     *
     * @param string $key
     */
    public function offsetUnset($key)
    {
        $this->set($key, null);
    }

    /**
     * Load a configuration file.
     *
     * @param string $file
     */
    public function load($file)
    {
        $this->files[] = $file;
    }

    /**
     * Dumps the configuration.
     *
     * @return string The dumped configuration.
     */
    public function dump()
    {
        return '<?php return '.var_export($this->values, true).';';
    }

    /**
     * Load the configuration from files.
     */
    protected function doLoad()
    {
        while ($file = array_shift($this->files)) {
            $this->values = $this->merge($this->values, $this->loader->load($file));
        }
    }

    protected function merge(array $current, array $new)
    {
        foreach ($new as $name => $value) {

            if ( is_string($name) && ( $important = $name[0] == '!' ) ) {
                $name = ltrim($name, '!');
            }

            if (isset($current[$name]) && is_array($value) && !$important) {
                $current[$name] = $this->merge($current[$name], $value);
            } elseif (is_string($name)) {
                $current[$name] = $this->doReplacements($value);
            } else {
                $current[] = $this->doReplacements($value);
            }
        }

        return $current;
    }

    protected function doReplacements($value)
    {
        if (!$this->replacements) {
            return $value;
        }

        if (is_array($value)) {

            foreach ($value as $key => $val) {
                $value[$key] = $this->doReplacements($val);
            }

            return $value;
        }

        if (is_string($value) && false !== strpos($value, '%')) {
            return preg_replace_callback('/\%([\w\.]+)\%/', array($this, 'doReplacementCallback'), $value);
        }

        return $value;
    }

    protected function doReplacementCallback($matches)
    {
        if (array_key_exists($key = $matches[1], $this->replacements)) {

            if (($closure = $this->replacements[$key]) instanceof \Closure) {
                $this->replacements[$key] = $closure();
            }

            return $this->replacements[$key];
        }

        return $matches[0];
    }
}
