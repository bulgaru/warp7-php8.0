<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

// add css
$this['asset']->addFile('css', 'css:theme.css');

?>

<!DOCTYPE HTML>
<html lang="<?php echo $this['config']->get('language'); ?>" dir="<?php echo $this['config']->get('direction'); ?>" class="uk-height-1-1 tm-error">

<head>
<?php echo $this->render('head', compact('error', 'title')); ?>
</head>

<body class="uk-height-1-1 uk-vertical-align uk-text-center">

	<div class="uk-vertical-align-middle uk-container-center">

		<i class="tm-error-icon uk-icon-frown-o"></i>

		<h1 class="tm-error-headline"><?php echo $error; ?></h1>

		<h2 class="uk-h3 uk-text-muted"><?php echo $title; ?></h2>

		<p><?php echo $message; ?></p>

	</div>

</body>
</html>