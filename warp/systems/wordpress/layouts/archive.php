<?php if (have_posts()) : ?>

    <?php if (is_category()) : ?>
        <?php /* <h1 class="page-title">Archive for the &#8216;<?php single_cat_title(); ?>&#8217; Category</h1> */ ?>
    <?php elseif (is_tag()) : ?>
        <h1 class="uk-h3"><?php printf(__('Posts Tagged %s', 'warp'), '&#8216;'.single_tag_title('', false).'&#8217;'); ?></h1>
    <?php elseif (is_day()) : ?>
        <h1 class="uk-h3"><?php printf(__('Archive for %s', 'warp'), get_the_date()); ?></h1>
    <?php elseif (is_month()) : ?>
        <h1 class="uk-h3"><?php printf(__('Archive for %s', 'warp'), get_the_date('F, Y')); ?></h1>
    <?php elseif (is_year()) : ?>
        <h1 class="uk-h3"><?php printf(__('Archive for %s', 'warp'), get_the_date('Y')); ?></h1>
    <?php elseif (is_author()) : ?>
        <h1 class="uk-h3"><?php _e('Author Archive', 'warp'); ?></h1>
    <?php elseif (isset($_GET['paged']) && !empty($_GET['paged'])) : ?>
        <h1 class="uk-h3"><?php _e('Blog Archives', 'warp'); ?></h1>
    <?php endif; ?>

    <?php echo $this->render('_posts'); ?>

    <?php echo $this->render("_pagination", array("type"=>"posts")); ?>

<?php else : ?>

    <?php if (is_category()) : ?>
        <h1 class="uk-h3"><?php printf(__("Sorry, but there aren't any posts in the %s category yet.", "warp"), single_cat_title('', false)); ?></h1>
    <?php elseif (is_date()) : ?>
        <h1 class="uk-h3"><?php _e("Sorry, but there aren't any posts with this date.", "warp"); ?></h1>
    <?php elseif (is_author()) : ?>
        <?php $userdata = get_userdatabylogin(get_query_var('author_name')); ?>
        <h1 class="uk-h3"><?php printf(__("Sorry, but there aren't any posts by %s yet.", "warp"), $userdata->display_name); ?></h1>
    <?php else : ?>
        <h1 class="uk-h3"><?php _e("No posts found.", "warp"); ?></h1>
    <?php endif; ?>

    <?php get_search_form(); ?>

<?php endif; ?>