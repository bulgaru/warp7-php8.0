<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

namespace Warp\Config\Loader;

/**
 * Loader for .json files.
 */
class JsonLoader implements LoaderInterface
{
    public function load($filename)
    {
        $config = $this->parseJson($filename);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \RuntimeException(sprintf('Invalid JSON provided "%s" in "%s"', $this->getJsonError(json_last_error()), $filename));
        }

        return $config ?: array();
    }

    public function supports($filename)
    {
        return (bool) preg_match('#\.json(\.dist)?$#', $filename);
    }

    protected function parseJson($filename)
    {
        return json_decode(file_get_contents($filename), true);
    }

    protected function getJsonError($code)
    {
        $errors = array(
            JSON_ERROR_DEPTH            => 'The maximum stack depth has been exceeded',
            JSON_ERROR_STATE_MISMATCH   => 'Invalid or malformed JSON',
            JSON_ERROR_CTRL_CHAR        => 'Control character error, possibly incorrectly encoded',
            JSON_ERROR_SYNTAX           => 'Syntax error',
            JSON_ERROR_UTF8             => 'Malformed UTF-8 characters, possibly incorrectly encoded',
        );

        return isset($errors[$code]) ? $errors[$code] : 'Unknown';
    }
}
