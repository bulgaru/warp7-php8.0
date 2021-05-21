<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

$html = array();

if (($checksums = $this['path']->path('theme:checksums')) && filesize($checksums)) {
	$this['checksum']->verify($this['path']->path('theme:'), $log);

	if ($count = count($log)) {

		$html[] = '<p>Some template files have been modified.</p>';
		$html[] = '<div class="uk-scrollable-box tm-width">';
		$html[] = '<ul class="uk-list uk-text-small uk-text-info">';
		foreach (array('modified', 'missing') as $type) {
			if (isset($log[$type])) {
				foreach ($log[$type] as $file) {
					$html[] = '<li class="'.$type.'">'.$file.($type == 'missing' ? ' (missing)' : null).'</li>';
				}
			}
		}
		$html[] = '</ul>';
		$html[] = '</div>';
		$html[] = '<p>To prevent modified files when using FTP, make sure the transfer mode is set to binary.</p>';

	} else {
		$html[] = '<p>Verification successful, no file modifications detected.</p>';
	}

} else {
	$html[] = '<p class="uk-text-danger">Checksum file is missing! Your template is maybe compromised.</p>';
}

echo implode("\n", $html);
