<meta charset="<?php bloginfo('charset'); ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<?php if($this['config']->get('responsive', true)): ?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php endif; ?>
<?php if (isset($error)): ?>
<title><?php echo $error; ?> - <?php echo $title; ?></title>
<?php else: ?>
<title><?php wp_title('&raquo;', true, 'right'); ?></title>
<?php endif; ?>
<link rel="shortcut icon" href="<?php echo $this['path']->url('theme:favicon.ico');?>">
<link rel="apple-touch-icon-precomposed" href="<?php echo $this['path']->url('theme:apple_touch_icon.png'); ?>">
<?php

//load header.php
get_header();

$this->output('wp_head');

// set body classes
$this['config']->set('body_classes', implode(' ', get_body_class($this['config']->get('body_classes'))));

// get styles and scripts
$styles  = $this['asset']->get('css');
$scripts = $this['asset']->get('js');

// load woocommerce style overrides
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( is_plugin_active('woocommerce/woocommerce.php') && $file = $this['path']->url('css:woocommerce.css')) {
    $styles->prepend($this['asset']->createFile($file));
}

// customizer mode
if ($this['config']['customizer']) {
    foreach ($this['config']['less']['files'] as $file => $less) {
        foreach ($styles as $style) {
            if ($url = $style->getUrl() and substr($url, -strlen($file)) == $file) {
                $style['data-file'] = $file;
                break;
            }
        }
    }
}
// developer mode
else if ($this['config']['dev_mode']) {

    // less files & filter
    $files  = array();
    $filter = $this['assetfilter']->create(array('CssImportResolver', 'CssRewriteUrl'));

    foreach ($styles as $style) {

        if (!$style instanceof Warp\Asset\FileAsset) continue;

        $file = sprintf('less:%s.less', basename($style->getPath(), '.css'));

        if ($this['path']->path($file)) {

            $source = $this['asset']->createFile($file)->getContent($filter).PHP_EOL;

            if ($this['config']['style'] == 'default') {
                $source .= $this['asset']->createFile('less:style.less')->getContent();
            } else {
                $source .= $this['asset']->createFile(sprintf('theme:styles/%s/style.less', $this['config']['style']))->getContent();
            }

            $files[] = array('target' => basename($style->getPath()), 'source' => $this['asset']->createString($source, array_merge($style->getOptions(), array('type' => 'text/less')))->getContent());

            $styles->replace($style, $this['asset']->createString('', array_merge($style->getOptions(), array('data-file' => basename($style->getPath())))));
        }
    }

    $this['asset']->addString('js', 'var less = { env: "development" }, files = '.json_encode($files).';');
    $this['asset']->addFile('js', 'warp:vendor/jquery/jquery-less.js');
    $this['asset']->addFile('js', 'warp:vendor/jquery/jquery-rtl.js');
    $this['asset']->addFile('js', 'warp:vendor/less/less-1.5.1.min.js');
    $this['asset']->addFile('js', 'warp:js/developer.js');
}
// compress styles and scripts
else if ($compression = $this['config']['compression'] or $this['config']['direction'] == 'rtl') {

    $options = array();
    $filters = array('CssImportResolver', 'CssRewriteUrl');

    // set options
    if ($compression == 3) {
        $options['Gzip'] = true;
    }

    // set filter
    if ($this['config']['direction'] == 'rtl') {
        $filters[] = 'CssRtl';
    }

    if ($compression >= 2 && ($this['useragent']->browser() != 'msie' || version_compare($this['useragent']->version(), '8.0', '>='))) {
        $filters[] = 'CssImageBase64';
    }

    if ($styles) {
        // cache styles and check for remote styles
        $styles = array($this['asset']->cache('theme.css', $styles, array_merge($filters, array('CssCompressor')), $options));
        foreach ($styles[0] as $style) {
            if ($style->getType() == 'File' && !$style->getPath()) {
                $styles[] = $style;
            }
        }
    }

    if ($scripts) {
        // cache scripts and check for remote scripts
        $scripts = array($this['asset']->cache('theme.js', $scripts, array('JsCompressor'), $options));
        foreach ($scripts[0] as $script) {
            if ($script->getType() == 'File' && !$script->getPath()) {
                $scripts[] = $script;
            }
        }
    }

}

// add styles
if ($styles) {
    foreach ($styles as $style) {
        if ($url = $style->getUrl()) {
            printf("<link %srel=\"stylesheet\" href=\"%s\">\n", isset($style['data-file']) ? 'data-file="'.$style['data-file'].'" ' : '', $url);
        } else {
            printf("<style %s>%s</style>\n", $this['field']->attributes($style->getOptions(), array('base_path', 'base_url')), $style->getContent());
        }
    }
}

// add scripts
if ($scripts) {
    foreach ($scripts as $script) {
        if ($url = $script->getUrl()) {
            printf("<script src=\"%s\"></script>\n", $url);
        } else {
            printf("<script>%s</script>\n", $script->getContent());
        }
    }
}

// add feed link
if (strlen($this['config']->get('rss_url',''))) {
    printf("<link href=\"%s\" rel=\"alternate\" type=\"application/rss+xml\" title=\"RSS 2.0\">\n", $this['config']->get('rss_url'));
}

$this->output('head');
