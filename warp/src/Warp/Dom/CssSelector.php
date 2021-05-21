<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

namespace Warp\Dom;

/**
 * Converts CSS Selectors to XPath Query.
 */
class CssSelector
{
    protected static $regex = array(
        'element'    => '/^\s*(\*|[\w\-]+)(?:\b|$)?/i',
        'id'         => '/^#([\w\-\*]+)(?:\b|$)/i',
        'class'      => '/^\.([\w\-\*]+)(?:\b|$)/i',
        'attr1'      => '/^\[((?:[\w-]+:)?[\w-]+)\]/i',
        'attr2'      => '/^\[\s*([^~\*\!\^\$\|=\s]+)\s*([~\*\^\!\$\|]?=)\s*["\']?([^"\'\]]*)["\']?\s*\]/i',
        'pseudo'     => '/^:((?:first|last|only)-child|(?:en|dis)abled|first|last|empty|checked|not|contains)(?:\((.*?)\))?(?:\b|$|(?=\s|[:+~>]))/i',
        'combinator' => '/^(?:\s*[>+~\s])?/i'
    );

    protected static $xpath = array(
        'id' => "@id = '%s'",
        'class' => "contains(concat(' ', normalize-space(@class), ' '), ' %s ')",
        'attr' => "@%s",
        'contains' => "contains(string(.), '%s')",
        'not' => 'not(%s)',
        'operators' => array("=" => "@%1 = '%3'", "!=" => "not(@%1) or @%1 != '%3'", "^=" => "starts-with(@%1, '%3')", "$=" => "substring(@%1, (string-length(@%1) - string-length('%3') + 1)) = '%3'", "*=" => "contains(@%1, '%3')", "~=" => "contains(concat(' ', normalize-space(@%1), ' '), ' %3 ')", "|=" => "@%1 = '%3' or starts-with(@%1, '%3-')"),
        'pseudos' => array('first-child' => 'not(preceding-sibling::*)', 'last-child' => 'not(following-sibling::*)', 'only-child' => 'not(preceding-sibling::* or following-sibling::*)', 'enabled' => "not(@disabled) and (@type!='hidden')", 'disabled' => "(@disabled) and (@type!='hidden')", 'first' => 'position() = 1', 'last' => 'last()', 'empty' => 'count(*) = 0 and (count(text()) = 0)', 'checked' => '@checked'),
        'combinators' => array('>' => 'child', '~' => 'general-sibling', '+' => 'adjacent-sibling')
    );

    protected static $cache = array();

    public static function toXPath($selector, $prefix = 'descendant-or-self::')
    {
        if (!isset(self::$cache[$prefix][$selector])) {

            $xpath = array();

            foreach (explode(',', $selector) as $sel) {
                if ($sel = trim($sel)) {
                    $xpath[] = self::convertSelector($sel, $prefix);
                }
            }

              if ($xpath = implode(' | ', $xpath)) {
                self::$cache[$prefix][$selector] = $xpath;
            } else {
                return null;
            }
        }

        return self::$cache[$prefix][$selector];
    }

    protected static function convertSelector($selector, $prefix)
    {
        $element  = array('element' => '*', 'combinator' => null, 'conditions' => array());
        $elements = array();
        $selector = trim($selector);
        $index    = 0;
        $last     = null;
        $xpath    = null;

        while (strlen($selector) > 0 && $selector != $last) {
            $last = $selector;

            // create element
            if (!isset($elements[$index])) {
                $elements[$index] = array_merge($element);
            }

            // match element name
            if (preg_match(self::$regex['element'], $selector, $matches)) {
                $elements[$index]['element'] = $matches[1];
                $selector = substr($selector, strlen($matches[0]));
            }

            // match id
            if (preg_match(self::$regex['id'], $selector, $matches)) {
                $elements[$index]['conditions'][] = sprintf(self::$xpath['id'], $matches[1]);
                $selector = substr($selector, strlen($matches[0]));
            }

            // match class name
            if (preg_match(self::$regex['class'], $selector, $matches)) {
                $elements[$index]['conditions'][] = sprintf(self::$xpath['class'], $matches[1]);
                $selector = substr($selector, strlen($matches[0]));
            }

            // match attribute presence
            if ($attr1 = preg_match(self::$regex['attr1'], $selector, $matches)) {
                $elements[$index]['conditions'][] = sprintf(self::$xpath['attr'], $matches[1]);
                $selector = substr($selector, strlen($matches[0]));
            }

            // match attribute and value
            if (!$attr1 && preg_match(self::$regex['attr2'], $selector, $matches)) {
                $elements[$index]['conditions'][] = str_replace(array('%1', '%3'), array($matches[1], $matches[3]), self::$xpath['operators'][$matches[2]]);
                $selector = substr($selector, strlen($matches[0]));
            }

            // match pseudo
            if (preg_match(self::$regex['pseudo'], $selector, $matches)) {

                if (isset(self::$xpath['pseudos'][$matches[1]])) {
                    $elements[$index]['conditions'][] = self::$xpath['pseudos'][$matches[1]];
                } elseif ($matches[1] == 'not') {
                    $elements[$index]['conditions'][] = sprintf(self::$xpath['not'], self::toXPath($matches[2]));
                } elseif ($matches[1] == 'contains') {
                    $elements[$index]['conditions'][] = sprintf(self::$xpath['contains'], $matches[2]);
                }

                $selector = substr($selector, strlen($matches[0]));
            }

            // match combinators
            if (preg_match(self::$regex['combinator'], $selector, $matches) && strlen($matches[0])) {
                $combinator = 'descendant';

                if (($comb = trim($matches[0])) && isset(self::$xpath['combinators'][$comb])) {
                    $combinator = self::$xpath['combinators'][$comb];
                }

                $elements[++$index] = array_merge($element, compact('combinator'));
                $selector = substr($selector, strlen($matches[0]));
            }

            $selector = trim($selector);
        }

        // create xpath expression
        foreach ($elements as $element) {

            switch ($element['combinator']) {

                case 'descendant':
                    $xpath .= '/descendant::';
                    break;

                case 'child':
                    $xpath .= '/child::';
                    break;

                case 'general-sibling':
                    $xpath .= '/following-sibling::';
                    break;

                case 'adjacent-sibling':
                    $xpath .= '/following-sibling::';

                    array_unshift($element['conditions'], 'position() = 1');

                    if ($element['element'] != '*') {
                        array_unshift($element['conditions'], sprintf("name() = '%s'", $element['element']));
                        $element['element'] = '*';
                    }

                    break;

                default:
                    $xpath .= $prefix;
            }

            $xpath .= $element['element'];

            if (count($element['conditions'])) {
                $xpath .= '[';

                foreach ($element['conditions'] as $i => $condition) {
                    $xpath .= $i == 0 ? $condition : sprintf(' and (%s)', $condition);
                }

                $xpath .= ']';
            }

        }

        return $xpath;
    }
}
