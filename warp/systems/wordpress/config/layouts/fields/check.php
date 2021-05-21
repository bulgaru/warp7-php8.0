<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

// check common
$this['check']->checkCommon();

// check writable
foreach (array($this['path']->path('cache:'), $this['path']->path('theme:')) as $directory) {
    $this['check']->checkWritable($directory);
}

// output
$critical = $this['check']->getIssues('critical');
$notice   = $this['check']->getIssues('notice');

if ($critical || $notice) {

    $label = array();

    if ($critical) {
        $label[] = count($critical).' critical';
    }

    if ($notice) {
        $label[] = count($notice).' potential';
    }

    echo '<p>'.implode(' and ', $label).' issue(s) detected.</p>';
    echo '<ul class="uk-list uk-list-line tm-width">';

    echo implode('', array_map(function($message) {
        return "<li class=\"uk-text-danger\"><i class=\"uk-icon-bolt\"></i> {$message}</li>";
    }, $critical));

    echo implode('', array_map(function($message) {
        return "<li class=\"uk-text-warning\"><i class=\"uk-icon-bolt\"></i> {$message}</li>";
    }, $notice));

    echo '</ul>';

} else {
    echo "<p>Warp engine operational and ready for take off.<p>";
}
