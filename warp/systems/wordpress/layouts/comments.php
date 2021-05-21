<?php if (comments_open() || have_comments()) : ?>

    <div id="comments" class="uk-margin">


        <?php if (have_comments()) : ?>

            <h2 class="uk-h3"><?php printf(__('Comments (%s)', 'warp'), get_comments_number()); ?></h2>

            <?php

                $classes = array("level1");

                if (get_option('comment_registration') && !is_user_logged_in()) {
                    $classes[] = "no-response";
                }

                if (get_option('thread_comments')) {
                    $classes[] = "nested";
                }

            ?>

            <ul class="uk-comment-list">
            <?php

                // single comment
                function mytheme_comment($comment, $args, $depth)
                {
                    global $user_identity;

                    $GLOBALS['comment'] = $comment;

                    $_GET['replytocom'] = get_comment_ID();
                    ?>
                    <li>
                        <article id="comment-<?php comment_ID(); ?>" class="uk-comment <?php echo ($comment->user_id > 0) ? 'uk-comment-primary' : '';?>">

                            <header class="uk-comment-header">

                                <?php echo get_avatar($comment, $size='50', null, 'Avatar'); ?>

                                <h3 class="uk-comment-title"><?php echo get_comment_author_link(); ?></h3>

                                <p class="uk-comment-meta">
                                    <time datetime="<?php echo get_comment_date('Y-m-d'); ?>"><?php printf(__('%1$s at %2$s', 'warp'), get_comment_date(), get_comment_time()) ?></time>
                                    | <a class="permalink" href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)) ?>">#</a>
                                    <?php edit_comment_link(__('Edit'),'| ','') ?>
                                </p>

                            </header>

                            <div class="uk-comment-body">

                                <?php comment_text(); ?>

                                <?php if (comments_open() && $args['max_depth'] > $depth) : ?>
                                <p class="js-reply"><a href="#" rel="<?php comment_ID(); ?>"><?php echo __('<i class="uk-icon-reply"></i> Reply', 'warp'); ?></a></p>
                                <?php endif; ?>

                                <?php if ($comment->comment_approved == '0') : ?>
                                <div class="uk-alert"><?php _e('Your comment is awaiting moderation.', 'warp'); ?></div>
                                <?php endif; ?>

                            </div>

                        </article>
                    <?php
                    unset($_GET['replytocom']);

                    // </li> is rendered by system
                }

                wp_list_comments('type=all&callback=mytheme_comment');
            ?>
            </ul>

            <?php echo $this->render("_pagination", array("type"=>"comments")); ?>

        <?php endif; ?>


        <div id="respond">

            <h2 class="uk-h3"><?php (comments_open()) ? comment_form_title(__('Leave a comment', 'warp')) : _e('Comments are closed', 'warp'); ?></h2>

            <?php if (comments_open()) : ?>

                <?php if (get_option('comment_registration') && !is_user_logged_in()) : ?>
                    <div class="uk-alert uk-alert-warning"><?php printf(__('You must be <a href="%s">logged in</a> to post a comment.', 'warp'), wp_login_url(get_permalink())); ?></div>
                <?php else : ?>

                    <form class="uk-form" action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post">

                        <?php if (is_user_logged_in()) : ?>

                            <?php global $user_identity; ?>

                            <p><?php printf(__('Logged in as <a href="%s">%s</a>.', 'warp'), get_option('siteurl').'/wp-admin/profile.php', $user_identity); ?> <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="<?php _e('Log out of this account', 'warp'); ?>"><?php _e('Log out &raquo;', 'warp'); ?></a></p>

                        <?php else : ?>

                            <?php
                                $req = get_option('require_name_email');
                                $commenter = wp_get_current_commenter();
                                $consent = empty( $commenter['comment_author_email'] ) ? '' : ' checked="checked"';
                            ?>

                            <div class="uk-form-row <?php if ($req) echo "required"; ?>">
                                <input type="text" name="author" placeholder="<?php _e('Name', 'warp'); ?> <?php if ($req) echo "*"; ?>" value="<?php echo esc_attr($comment_author); ?>" <?php if ($req) echo "aria-required='true'"; ?>>
                            </div>

                            <div class="uk-form-row <?php if ($req) echo "required"; ?>">
                                <input type="text" name="email" placeholder="<?php _e('E-mail', 'warp'); ?> <?php if ($req) echo "*"; ?>" value="<?php echo esc_attr($comment_author_email); ?>" <?php if ($req) echo "aria-required='true'"; ?>>
                            </div>

                            <div class="uk-form-row">
                                <input type="text" name="url" placeholder="<?php _e('Website', 'warp'); ?>" value="<?php echo esc_attr($comment_author_url); ?>">
                            </div>

                            <div class="uk-form-row">
                                <input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes" <?php echo $consent ?> /> <?php echo __( 'Save my name, email, and website in this browser for the next time I comment.' ) ?>
                            </div>

                        <?php endif; ?>

                        <div class="uk-form-row">
                            <textarea name="comment" id="comment" cols="80" rows="5" tabindex="4"></textarea>
                        </div>

                        <div class="uk-form-row actions">
                            <button class="uk-button uk-button-primary" name="submit" type="submit" id="submit" tabindex="5"><?php _e('Submit Comment', 'warp'); ?></button>
                            <?php comment_id_fields(); ?>
                        </div>
                        <?php global $post; do_action('comment_form', $post->ID); ?>

                    </form>

                <?php endif; ?>

            <?php endif; ?>

        </div>


    </div>

    <script type="text/javascript">

        jQuery(function($) {

            var respond = $("#respond");

            $("p.js-reply > a").bind("click", function(){

                var id = $(this).attr('rel');

                respond.find(".comment-cancelReply:first").remove();

                $('<a><?php echo __("Cancel");?></a>').addClass('comment-cancelReply uk-margin-left').attr('href', "#respond").bind("click", function(){
                    respond.find(".comment-cancelReply:first").remove();
                    respond.appendTo($('#comments')).find("[name=comment_parent]").val(0);

                    return false;
                }).appendTo(respond.find(".actions:first"));

                respond.find("[name=comment_parent]").val(id);
                respond.appendTo($("#comment-"+id));

                return false;

            });
        });

    </script>

<?php endif;
