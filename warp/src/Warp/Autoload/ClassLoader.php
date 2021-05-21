<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

namespace Warp\Autoload;

/**
 * ClassLoader implements a PSR-0 class loader.
 *
 * Based on Composer (https://github.com/composer/composer, Fabien Potencier <fabien@symfony.com>, Jordi Boggiano <j.boggiano@seld.be>, MIT License)
 */
class ClassLoader
{
    private $prefixes = array();
    private $fallbackDirs = array();
    private $useIncludePath = false;
    private $classMap = array();

    public function getPrefixes()
    {
        return call_user_func_array('array_merge', $this->prefixes);
    }

    public function getFallbackDirs()
    {
        return $this->fallbackDirs;
    }

    public function getClassMap()
    {
        return $this->classMap;
    }

    /**
     * @param array $classMap Class to filename map
     */
    public function addClassMap(array $classMap)
    {
        if ($this->classMap) {
            $this->classMap = array_merge($this->classMap, $classMap);
        } else {
            $this->classMap = $classMap;
        }
    }

    /**
     * Registers a set of classes, merging with any others previously set.
     *
     * @param string       $prefix  The classes prefix
     * @param array|string $paths   The location(s) of the classes
     * @param bool         $prepend Prepend the location(s)
     */
    public function add($prefix, $paths, $prepend = false)
    {
        if (!$prefix) {
            if ($prepend) {
                $this->fallbackDirs = array_merge(
                    (array) $paths,
                    $this->fallbackDirs
                );
            } else {
                $this->fallbackDirs = array_merge(
                    $this->fallbackDirs,
                    (array) $paths
                );
            }

            return;
        }

        $first = $prefix[0];
        if (!isset($this->prefixes[$first][$prefix])) {
            $this->prefixes[$first][$prefix] = (array) $paths;

            return;
        }
        if ($prepend) {
            $this->prefixes[$first][$prefix] = array_merge(
                (array) $paths,
                $this->prefixes[$first][$prefix]
            );
        } else {
            $this->prefixes[$first][$prefix] = array_merge(
                $this->prefixes[$first][$prefix],
                (array) $paths
            );
        }
    }

    /**
     * Registers a set of classes, replacing any others previously set.
     *
     * @param string       $prefix  The classes prefix
     * @param array|string $paths   The location(s) of the classes
     */
    public function set($prefix, $paths)
    {
        if (!$prefix) {
            $this->fallbackDirs = (array) $paths;

            return;
        }
        $this->prefixes[substr($prefix, 0, 1)][$prefix] = (array) $paths;
    }

    /**
     * Turns on searching the include path for class files.
     *
     * @param bool $useIncludePath
     */
    public function setUseIncludePath($useIncludePath)
    {
        $this->useIncludePath = $useIncludePath;
    }

    /**
     * Can be used to check if the autoloader uses the include path to check
     * for classes.
     *
     * @return bool
     */
    public function getUseIncludePath()
    {
        return $this->useIncludePath;
    }

    /**
     * Registers this instance as an autoloader.
     *
     * @param bool $prepend Whether to prepend the autoloader or not
     */
    public function register($prepend = false)
    {
        spl_autoload_register(array($this, 'loadClass'), true, $prepend);
    }

    /**
     * Unregisters this instance as an autoloader.
     */
    public function unregister()
    {
        spl_autoload_unregister(array($this, 'loadClass'));
    }

    /**
     * Loads the given class or interface.
     *
     * @param  string    $class The name of the class
     * @return bool|null True if loaded, null otherwise
     */
    public function loadClass($class)
    {
        if ($file = $this->findFile($class)) {
            include $file;

            return true;
        }
    }

    /**
     * Finds the path to the file where the class is defined.
     *
     * @param string $class The name of the class
     *
     * @return string|false The path if found, false otherwise
     */
    public function findFile($class)
    {
        if ('\\' == $class[0]) {
            $class = substr($class, 1);
        }

        if (isset($this->classMap[$class])) {
            return $this->classMap[$class];
        }

        if (false !== $pos = strrpos($class, '\\')) {
            // namespaced class name
            $classPath = strtr(substr($class, 0, $pos), '\\', DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            $className = substr($class, $pos + 1);
        } else {
            // PEAR-like class name
            $classPath = null;
            $className = $class;
        }

        $classPath .= strtr($className, '_', DIRECTORY_SEPARATOR) . '.php';

        $first = $class[0];
        if (isset($this->prefixes[$first])) {
            foreach ($this->prefixes[$first] as $prefix => $dirs) {
                if (0 === strpos($class, $prefix)) {
                    foreach ($dirs as $dir) {
                        if (file_exists($dir . DIRECTORY_SEPARATOR . $classPath)) {
                            return $dir . DIRECTORY_SEPARATOR . $classPath;
                        }
                    }
                }
            }
        }

        foreach ($this->fallbackDirs as $dir) {
            if (file_exists($dir . DIRECTORY_SEPARATOR . $classPath)) {
                return $dir . DIRECTORY_SEPARATOR . $classPath;
            }
        }

        if ($this->useIncludePath && $file = stream_resolve_include_path($classPath)) {
            return $file;
        }

        return $this->classMap[$class] = false;
    }
}
