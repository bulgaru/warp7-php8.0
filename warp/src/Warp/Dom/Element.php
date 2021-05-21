<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

namespace Warp\Dom;

/**
 * DOM element class with extended attributes and functions.
 */
class Element extends \DOMElement
{
    public function first($query)
    {
        if ($matches = $this->find($query)) {
            if ($matches->length) {
                return $matches->item(0);
            }
        }

        return null;
    }

    public function find($query)
    {
        return $this->query(CssSelector::toXPath($query, 'descendant::'));
    }

    public function query($expression)
    {
        return $this->ownerDocument->xpath()->query($expression, $this);
    }

    public function parent()
    {
        return $this->parentNode;
    }

    public function next()
    {
        $sibling = $this->nextSibling;

        do {

            if ($sibling->nodeType == XML_ELEMENT_NODE) {
                break;
            }

        } while ($sibling = $sibling->nextSibling);

        return $sibling;
    }

    public function prev()
    {
        $sibling = $this->previousSibling;

        do {

            if ($sibling->nodeType == XML_ELEMENT_NODE) {
                break;
            }

        } while ($sibling = $sibling->previousSibling);

        return $sibling;
    }

    public function hasChildren()
    {
        return $this->hasChildNodes();
    }

    public function children($query = null)
    {
        $children = array();

        if (!$this->hasChildren()) {
            return $children;
        }

        if ($query == null) {

            foreach ($this->childNodes as $child) {
                if ($child->nodeType == XML_ELEMENT_NODE) {
                    $children[] = $child;
                }
            }

            return $children;
        }

        return $this->query(CssSelector::toXPath($query, 'child::'));
    }

    public function removeChildren()
    {
        while ($child = $this->firstChild) {
            $this->removeChild($child);
        }

        return $this;
    }

    public function warp_before($data)
    {
        $data = $this->prepareInsert($data);
        $this->parentNode->insertBefore($data, $this);

        return $this;
    }

    public function warp_after($data)
    {
        $data = $this->prepareInsert($data);

        if (isset($this->nextSibling)) {
            $this->parentNode->insertBefore($data, $this->nextSibling);
        } else {
            $this->parentNode->appendChild($data);
        }

        return $this;
    }

    public function warp_prepend($data)
    {
        $data = $this->prepareInsert($data);

        if (isset($data)) {
            if ($this->hasChildren()) {
                $this->insertBefore($data, $this->firstChild);
            } else {
                $this->appendChild($data);
            }
        }

        return $this;
    }

    public function warp_append($data)
    {
        $data = $this->prepareInsert($data);

        if (isset($data)) {
            $this->appendChild($data);
        }

        return $this;
    }

    public function warp_replaceWith($data)
    {
        $data = $this->prepareInsert($data);

        if (isset($data)) {
            $this->parent()->replaceChild($data, $this);
        }

        return $data;
    }

    public function wrap($data)
    {
        $data = $this->prepareInsert($data);

        if (empty($data)) {
            return $this;
        }

        self::wrapNode($this, $data);

        return $this;
    }

    public function text($text = null)
    {
        if (isset($text)) {
            $this->removeChildren();
            $this->appendChild($this->ownerDocument->createTextNode($text));
            return $this;
        }

        return $this->textContent;
    }

    public function html($markup = null)
    {
        if (isset($markup)) {
            $this->removeChildren();
            $this->append($markup);
            return $this;
        }

        // fix selfclosing tags
        if (false !== $html = $this->ownerDocument->saveXML($this)) {
            $html = preg_replace('#(<(div|span|i)(\s[^>]*)?)/>#i', '$1></$2>', $html);
        }

        return $html;
    }

    public function tag()
    {
        return $this->tagName;
    }

    public function val($value = null)
    {
        if (isset($value)) {
            return $this->attr('value', $value);
        }

        return $this->attr('value');
    }

    public function hasClass($class)
    {
        return self::attrClass('has', $this, $class);
    }

    public function addClass($class)
    {
        return self::attrClass('add', $this, $class);
    }

    public function removeClass($class)
    {
        return self::attrClass('remove', $this, $class);
    }

    public function toggleClass($class)
    {
        return self::attrClass('toggle', $this, $class);
    }

    public function attr($name = null, $value = null)
    {
        if (is_null($name)) {
            $attributes = array();

            foreach ($this->attributes as $name => $node) {
                $attributes[$name] = $node->value;
            }

            return $attributes;
        }

        if (isset($value)) {
            $this->setAttribute($name, $value);
            return $this;
        }

        return $this->getAttribute($name);
    }

    public function removeAttr($name)
    {
        $this->removeAttribute($name);
        return $this;
    }

    protected function prepareInsert($item)
    {
        if (empty($item)) {
            return;
        }

        if (is_string($item)) {

            $item = Entities::replaceAllEntities($item);
            $frag = $this->ownerDocument->createDocumentFragment();

            try {
                $frag->appendXML($item);
            } catch (Exception $e) {}

            return $frag;
        }

        if ($item instanceof \DOMNode) {

            if ($item->ownerDocument !== $this->ownerDocument) {
                return $this->ownerDocument->importNode($item, true);
            }

            return $item;
        }

    }

    protected static function wrapNode(\DOMNode $node, \DOMNode $wrapper)
    {
        if ($wrapper->hasChildNodes()) {
            $deepest = self::deepestNode($wrapper);
            $wrapper = $deepest[0];
        }

        $parent = $node->parentNode;
        $parent->insertBefore($wrapper, $node);
        $wrapper->appendChild($parent->removeChild($node));
    }

    protected static function deepestNode(\DOMNode $node, $depth = 0, $current = null, &$deepest = null)
    {
        if (!isset($current)) $current = array($node);
        if (!isset($deepest)) $deepest = $depth;

        if ($node->hasChildNodes()) {
            foreach ($node->childNodes as $child) {
                if ($child->nodeType === XML_ELEMENT_NODE) {
                    $current = self::deepestNode($child, $depth + 1, $current, $deepest);
                }
            }
        } elseif ($depth > $deepest) {
            $current = array($node);
            $deepest = $depth;
        } elseif ($depth === $deepest) {
            $current[] = $node;
        }

        return $current;
    }

    protected static function attrClass($action, \DOMNode $node, $class)
    {
        $classes = $node->getAttribute('class');
        $found   = stripos($classes, $class) !== false && in_array(strtolower($class), explode(' ', strtolower($classes)));

        if ($action == 'has') {
            return $found;
        }

        if ($action == 'toggle') {
            $action = $found ? 'remove' : 'add';
        }

        if ($action == 'add' && !$found) {
            $node->setAttribute('class', trim(preg_replace('/\s{2,}/i', ' ', $classes.' '.$class)));
        }

        if ($action == 'remove' && $found) {

            $classes = trim(preg_replace('/\s{2,}/i', ' ', preg_replace('/(^|\s)'.preg_quote($class, '/').'(?:\s|$)/i', ' ', $classes)));

            if ($classes !== '') {
                $node->setAttribute('class', $classes);
            } else {
                $node->removeAttribute('class');
            }
        }

        return $node;
    }
}
