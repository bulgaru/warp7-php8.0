<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

$styles = array('default');
$styles_css = array();

if ($path = $this['path']->path('theme:styles')) {
    foreach (glob("$path/*") as $dir) {

        $styles[] = $style = basename($dir);

        if (!file_exists($dir.'/style.less')) {
            $styles_css[] = $style;
        }
    }
}

?>
<p class="uk-form-controls-condensed">
    <select name="<?php echo $name ?>" data-selected="<?php echo $value ?>" data-style-css="<?php echo htmlspecialchars(json_encode($styles_css)) ?>" data-style-selector>
        <?php
            foreach ($styles as $style) {
                printf('<option value="%s">%s</option>', $style, $style);
            }
        ?>
    </select>
</p>