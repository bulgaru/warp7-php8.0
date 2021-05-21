<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/


// set gzip handler
if (extension_loaded('zlib') && !ini_get('zlib.output_compression')) @ob_start('ob_gzhandler');

// include file
if (isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING']) {

	$query = (string) preg_replace('/[^A-Z0-9_\.-]/i', '', $_SERVER['QUERY_STRING']);

	if (($file = realpath(dirname(__FILE__).DIRECTORY_SEPARATOR.$query)) && is_file($file)) {
		if ($type = pathinfo($file, PATHINFO_EXTENSION)) {
			
			// set header
			if ($type == 'css') header('Content-type: text/css; charset=UTF-8');
			if ($type == 'js') header('Content-type: application/x-javascript');
			header('Cache-Control: max-age=86400');

			// load file
			include($file);

		}
	}

}