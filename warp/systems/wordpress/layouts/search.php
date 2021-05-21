<?php if (have_posts()) : ?>

    <h1 class="uk-h3"><?php _e('Search Results for', 'warp'); ?> &#8216;<?php echo stripslashes(strip_tags(get_search_query()));?>&#8217;</h1>

    <?php
        // loop result
        while (have_posts()) {
            the_post();

            ?>
                <article id="item-<?php the_ID(); ?>" class="uk-article">

                    <h1 class="uk-article-title"><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>

                    <p class="uk-article-meta">
                        <?php
                            $date = '<time datetime="'.get_the_date('Y-m-d').'">'.get_the_date().'</time>';
                            printf(__('Written by %s on %s. Posted in %s', 'warp'), '<a href="'.get_author_posts_url(get_the_author_meta('ID')).'" title="'.get_the_author().'">'.get_the_author().'</a>', $date, get_the_category_list(', '));
                        ?>
                    </p>

                    <?php the_excerpt(); ?>

                    <ul class="uk-subnav uk-subnav-line">
                        <li><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php _e('Continue Reading', 'warp'); ?></a></li>
                        <?php if(comments_open()) : ?>
                            <li><?php comments_popup_link(__('No Comments', 'warp'), __('1 Comment', 'warp'), __('% Comments', 'warp'), "", ""); ?></li>
                        <?php endif; ?>
                    </ul>

                    <?php edit_post_link(__('Edit this post.', 'warp'), '<p class="edit">','</p>'); ?>
                </article>
            <?php
        }
    ?>

<?php echo $this->render("_pagination", array("type"=>"posts")); ?></p>

<?php else : ?>

    <h1><?php _e('No posts found. Try a different search?', 'warp'); ?></h1>
    <?php get_search_form(); ?>

<?php endif; ?>