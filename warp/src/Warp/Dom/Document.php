<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

namespace Warp\Dom;

/**
 * DOM document class with extended attributes and functions.
 */
class Document extends \DOMDocument
{
    /**
     * @var object
     */
    public $xpath;

    public function __construct($version = '1.0', $encoding = 'UTF-8')
    {
        parent::__construct($version, $encoding);

        // set node class
        $this->registerNodeClass('DOMElement', 'Warp\Dom\Element');
    }

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
        return $this->xpath()->query(CssSelector::toXPath($query, 'descendant::'));
    }

    public function query($expression)
    {
        return $this->xpath()->query($expression);
    }

    public function xpath()
    {
        if (empty($this->xpath)) {
            $this->xpath = new \DOMXPath($this);
        }

        return $this->xpath;
    }
}
