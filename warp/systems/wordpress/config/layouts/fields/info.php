<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

$info = array();

if ($xml = $this['dom']->create($this['path']->path('theme:theme.xml'), 'xml')) {
    $info[] = array("name" => $xml->first("name")->text(), "version"=> $xml->first("version")->text());
}

if ($xml = $this['dom']->create($this['path']->path('warp:warp.xml'), 'xml')) {
    $info[] = array("name" => "Warp Framework", "version"=> $xml->first("version")->text());
}

?>

<table class="uk-table uk-table-condensed tm-width">
    <thead>
        <tr>
            <th>Name</th>
            <th class="uk-text-right">Version</th>
        </tr>
    </thead>
    <tbody>
    	<?php foreach ($info as &$value): ?>
        <tr>
            <td><?php echo $value['name']; ?></td>
            <td class="uk-text-right"><?php echo $value['version']; ?></td>
        </tr>
	    <?php endforeach; ?>
    </tbody>
</table>