<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

global $wp_query;

$queried_object = $wp_query->get_queried_object();

// output content from header/footer mode
if ($this->has('content')) {
    return $this->output('content');
}

$content = '';

if (is_home()) {
    $content = 'index';
} elseif (is_page()) {
    $content = 'page';
} elseif (is_attachment()) {
    $content = 'attachment';
} elseif (is_single()) {

    $content = 'single';

    if (is_object($queried_object)) {
        // load a single-{post-type}.php file if it exists
        if ($this["path"]->path("layouts:single-{$queried_object->post_type}.php")) {
            $content = 'single-' . $queried_object->post_type;
        }
        // load a {post-type}.php file if it exists
        elseif ($this["path"]->path("layouts:{$queried_object->post_type}.php")) {
            $content = $queried_object->post_type;
        }
    }

} elseif (is_search()) {
    $content = 'search';
} elseif (is_archive() && is_author()) {
    $content = 'author';
} elseif (is_category()) {

    $content = 'archive';

    if (is_object($queried_object)) {
        // load a category-{category-slug}.php file if it exists
        if ($this["path"]->path("layouts:category-{$queried_object->category_nicename}.php")) {
            $content = 'category-' . $queried_object->category_nicename;
        }
        // load a category-{category-id}.php file if it exists
        elseif ($this["path"]->path("layouts:category-{$queried_object->term_taxonomy_id}.php")) {
            $content = 'category-' . $queried_object->term_taxonomy_id;
        }
        // load a category.php file if it exists
        elseif ($this["path"]->path("layouts:category.php")) {
            $content = 'category';
        }
    }

} elseif (is_tag()) {

    $content = 'archive';

    if (is_object($queried_object)) {
        // load a tag-{tag-slug}.php file if it exists
        if ($this["path"]->path("layouts:tag-{$queried_object->slug}.php")) {
            $content = 'tag-' . $queried_object->slug;
        }
        // load a tag-{tag-id}.php file if it exists
        elseif ($this["path"]->path("layouts:tag-{$queried_object->term_taxonomy_id}.php")) {
            $content = 'tag-' . $queried_object->term_taxonomy_id;
        }
        // load a tag.php file if it exists
        elseif ($this["path"]->path("layouts:tag.php")) {
            $content = 'tag';
        }
    }

} elseif (is_tax()) {

    $content = 'archive';

    if (is_object($queried_object)) {
        // load a taxonomy-{taxonomy}-{taxonomy-term}.php file if it exists
        if ($this["path"]->path("layouts:taxonomy-{$queried_object->taxonomy}-{$queried_object->slug}.php")) {
            $content = 'taxonomy-' . $queried_object->taxonomy . '-' . $queried_object->slug;
        }
        // load a taxonomy-{taxonomy}-{taxonomy-id}.php file if it exists
        elseif ($this["path"]->path("layouts:taxonomy-{$queried_object->taxonomy}-{$queried_object->term_taxonomy_id}.php")) {
            $content = 'taxonomy-' . $queried_object->taxonomy . '-' . $queried_object->term_taxonomy_id;
        }
        // load a taxonomy-{taxonomy}.php file if it exists
        elseif ($this["path"]->path("layouts:taxonomy-{$queried_object->taxonomy}.php")) {
            $content = 'taxonomy-' . $queried_object->taxonomy;
        }
        // load a taxonomy.php file if it exists
        elseif ($this["path"]->path("layouts:taxonomy.php")) {
            $content = 'taxonomy';
        }
    }

} elseif (is_archive()) {

    $content = 'archive';

    if (is_object($queried_object)) {
        // load a archive-{custom-posttype}.php file if it exists
        if ($this["path"]->path("layouts:archive-{$queried_object->name}.php")) {
            $content = 'archive-' . $queried_object->name;
        }
    }

} elseif (is_404()) {
    $content = '404';
}

echo $this->render(apply_filters('warp_content', $content));
