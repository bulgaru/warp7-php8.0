<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

namespace Warp\Wordpress\Helper;

use Warp\Helper\AbstractHelper;
use Warp\Warp;

/*
 *  Wordpress widget helper class, provides simplyfied access to wordpress widgets
 */
class WidgetsHelper extends AbstractHelper
{
    /**
     * @var array
     */
    public $widgets;

    /**
     * @var array
     */
    protected $loaded;

    /**
     * Class constructor.
     *
     * @param Warp $warp
     */
    public function __construct(Warp $warp)
    {
        parent::__construct($warp);
    }

    /**
     * Retrieve a widget by id
     *
     * @global array $wp_registered_widgets
     * @param string $id Widget ID
     * @return \stdClass
     */
    public function get($id)
    {
        global $wp_registered_widgets;

        $widget = null;

        if (isset($wp_registered_widgets[$id]) && ($data = $wp_registered_widgets[$id])) {
            $widget = new \stdClass;

            foreach (array('id', 'name', 'classname', 'description') as $var) {
                $widget->$var = isset($data[$var]) ? $data[$var] : null;
            }

            if (isset($data['callback']) && is_array($data['callback']) && ($object = current($data['callback']))) {
                if (is_a($object, 'WP_Widget')) {

                    $widget->type = $object->id_base;

                    if (isset($data['params'][0]['number'])) {

                        $number = $data['params'][0]['number'];
                        $params = get_option($object->option_name);

                        if (false === $params && isset($object->alt_option_name)) {
                            $params = get_option($object->alt_option_name);
                        }

                        if (isset($params[$number])) {
                            $widget->params = $params[$number];
                        }
                    }
                }
            } elseif ($id == 'nav_menu-0') {
                $widget->type = 'nav_menu';
            }

            if (empty($widget->name)) {
                $widget->name = ucfirst($widget->type);
            }

            if (empty($widget->params)) {
                $widget->params = array();
            }

            $widget->display = $this->display($widget);
        }

        return $widget;
    }

    /**
     * Retrieve widgets
     *
     * @param string $position
     * @return stdClass[]
     */
    public function getWidgets($position = null)
    {
        if (empty($this->widgets)) {
            foreach (wp_get_sidebars_widgets() as $pos => $ids) {

                if (!is_array($ids) || empty($ids)) {
                    continue;
                }

                $this->widgets[$pos] = array();

                foreach ($ids as $id) {
                    if ($widget = $this->get($id)) {
                        $this->widgets[$pos][$id] = $widget;
                    }
                }
            }
        }

        if (!is_null($position)) {
            return isset($this->widgets[$position]) ? $this->widgets[$position] : array();
        }

        return $this->widgets;
    }

    /**
     * Retrieve the active module count at a position
     *
     * @param string[] $positions
     * @return int
     */
    public function count($positions)
    {
        $positions = explode('+', $positions);
        $widgets   = $this->getWidgets();
        $count     = 0;

        foreach ($positions as $pos) {
            $pos = trim($pos);

            if (isset($widgets[$pos])) {
                foreach ($widgets[$pos] as $widget) {
                    if ($widget->display) {
                        $count += 1;
                    }
                }
            }

            if (!$count && ($this['system']->isPreview($pos) || ($pos == 'menu' && has_nav_menu('main_menu')))) {
                $count += 1;
            }
        }

        return $count;
    }

    /**
     * Shortcut to render a position
     *
     * @param string $position
     * @param array $args
     * @return string
     */
    public function render($position, $args = array())
    {
        // set position in arguments
        $args['position'] = $position;

        return $this['template']->render('widgets', $args);
    }

    /**
     * Register a position
     *
     * @param string[] $positions
     */
    public function register($positions)
    {
        $positions = (array) $positions;

        foreach ($positions as $name) {
            register_sidebar(array(
                'name' => $name,
                'id' => $name,
                'description' => '',
                'before_widget' => '<!--widget-%1$s<%2$s>-->',
                'after_widget' => '<!--widget-end-->',
                'before_title' => '<!--title-start-->',
                'after_title' => '<!--title-end-->',
            ));
        }
    }

    /**
     * Checks if a widget should be displayed
     *
     * @param stdClass $widget
     * @return boolean
     */
    protected function display($widget)
    {
        if (!$assignment = $this['config']->get("widgets.{$widget->id}.assignment") or in_array('*', $assignment)) {
            return true;
        }

        $query = $this['system']->getQuery();

        foreach ($query as $q) {

           if (in_array($q, $assignment)) {

                switch ($q) {
                    case "page":

                        if (is_home()) {
                            return in_array('home', $assignment);
                        }

                        if (is_front_page()) {
                            return in_array('front_page', $assignment);
                        }

                    default:
                        return true;
                }
            }
        }

        return false;
    }

	/**
	 * Retrieve module objects for a position
	 *
	 * @param  string $position
	 * @return array
	 */
    public function load($position)
    {
        if (!isset($this->loaded[$position])) {

            $widgets = array();

            if (!function_exists('dynamic_sidebar')) {
                return $widgets;
            }

            // get widgets
            ob_start();
            $result = dynamic_sidebar($position);
            $position_output = ob_get_clean();

            if ($position == 'menu') {
                $result = true;
                $position_output = $this['template']->render('menu').((string) $position_output);
            }

            // handle preview
            if (!$result && $this['system']->isPreview($position)) {
                $result = true;
                $position_output = $this['template']->render('preview', compact('position'));
            }

            if (!$result) {
                return $widgets;
            }

            $parts   = explode('<!--widget-end-->', $position_output);

            //prepare widgets
            foreach ($parts as $part) {

                if (!preg_match('/<!--widget-([a-z0-9-_]+)(?:<([^>]*)>)?-->/smU', $part, $matches)) continue;

                $widget  = $this->get($matches[1]);
                $suffix  = isset($matches[2]) ? $matches[2] : '';
                $content = str_replace($matches[0], '', $part);
                $title   = '';

                // display it ?
                if (!($widget && $widget->display)) continue;

                // has title ?
                if (preg_match('/<!--title-start-->(.*)<!--title-end-->/smU', $content, $matches)) {
                    $content = str_replace($matches[0], '', $content);
                    $title = $matches[1];
                }

                $widget->title     = strip_tags($title);
                $widget->showtitle = $this['config']->get("widgets.{$widget->id}.title", 1);
                $widget->content   = $content;
                $widget->position  = $position;
                $widget->menu      = $widget->type == 'nav_menu';
                $widget->suffix    = $suffix;

                $widgets[] = $widget;
            }
            $this->loaded[$position] = $widgets;
        }
        return $this->loaded[$position];
    }
}
