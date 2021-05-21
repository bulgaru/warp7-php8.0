<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

namespace Warp\Helper;

/**
 * Field helper class to render HTML input fields.
 */
class FieldHelper extends AbstractHelper
{
    /**
     *  Render a field like text, select or radio button.
     *
     * @param string $type
     * @param string $name
     * @param string $value
     * @param object $node
     * @param array  $args
     *
     * @return string
     */
    public function render($type, $name, $value, $node, $args = array())
    {
        $args['control'] = $this;
        $args['name']    = $name;
        $args['value']   = $value;
        $args['node']    = $node;

        return $this['template']->render('config:layouts/fields/'.$type, $args);
    }

    /**
     * Create html attribute string from array.
     *
     * @param array $attributes
     * @param array $ignore
     *
     * @return string
     */
    public function attributes($attributes, $ignore = array())
    {
        $attribs = array();
        $ignore  = (array) $ignore;

        foreach ($attributes as $name => $value) {
            if (in_array($name, $ignore)) continue;

            $attribs[] = sprintf('%s="%s"', $name, htmlspecialchars($value));
        }

        return implode(' ', $attribs);
    }
}
