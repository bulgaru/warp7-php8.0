<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

global $comments, $comment;

// init vars
$number   = (int) max(isset($widget->params['number']) ? $widget->params['number'] : 5, 1);
$comments = get_comments(array('number' => $number, 'status' => 'approve'));

if ($comments) : ?>
<ul class="uk-comment-list">

    <?php foreach ((array) $comments as $comment) : ?>
    <li>

        <article class="uk-comment">

            <header class="uk-comment-header">

                <?php echo get_avatar($comment, $size='35', null, 'Avatar'); ?>

                <h4 class="uk-comment-title">
                    <?php echo get_comment_author_link(); ?>
                </h4>

                <p class="uk-comment-meta">
                    <time datetime="<?php echo get_comment_date('Y-m-d'); ?>"><?php comment_date(); ?></time>
                    | <a class="permalink" href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)) ?>">#</a>
                </p>

            </header>

            <div class="uk-comment-body">
                <?php comment_text(); ?>
            </div>

        </article>

    </li>
    <?php endforeach; ?>

</ul>
<?php endif;
