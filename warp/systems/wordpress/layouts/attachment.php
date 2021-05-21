<?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>

    <article class="uk-article">

        <h1 class="uk-article-title"><?php the_title(); ?></h1>

        <p class="uk-article-meta">
            <?php
                $date = '<time datetime="'.get_the_date('Y-m-d').'">'.get_the_date().'</time>';
                printf(__('Published by %s on %s', 'warp'), '<a href="'.get_author_posts_url(get_the_author_meta('ID')).'" title="'.get_the_author().'">'.get_the_author().'</a>', $date);
            ?>.

            <?php
                if (wp_attachment_is_image()) {
                    $metadata = wp_get_attachment_metadata();
                    printf(__('Full size is %s pixels.', 'warp'),
                        sprintf('<a href="%1$s" title="%2$s">%3$s&times;%4$s</a>',
                            wp_get_attachment_url(),
                            esc_attr(__('Link to full-size image', 'warp')),
                            $metadata['width'],
                            $metadata['height']
                        )
                    );
                }
            ?>

        </p>

        <p><a href="<?php echo wp_get_attachment_url(); ?>" title="<?php the_title_attribute(); ?>"><?php echo wp_get_attachment_image($post->ID, 'full-size'); ?></a></p>

        <?php edit_post_link(__('Edit this attachment.', 'warp'), '<p><i class="uk-icon-pencil"></i> ','</p>'); ?>

    </article>

    <?php comments_template(); ?>

    <?php endwhile; ?>
<?php endif; ?>