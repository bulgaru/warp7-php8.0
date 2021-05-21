<h1><?php _e('Author Archive', 'warp'); ?></h1>

<?php the_post(); ?>

<?php if (get_the_author_meta('description')) : ?>
<div class="uk-panel uk-panel-box uk-margin">

    <div class="uk-align-medium-left">

        <?php echo get_avatar(get_the_author_meta('user_email')); ?>

    </div>

    <h2 class="uk-h3 uk-margin-top-remove"><?php the_author(); ?></h2>

    <div class="uk-margin"><?php the_author_meta('description'); ?></div>

</div>
<?php endif; ?>

<?php rewind_posts(); ?>
<?php if (have_posts()) : ?>

    <?php echo $this->render('_posts'); ?>

    <?php echo $this->render("_pagination", array("type"=>"posts")); ?></p>

<?php endif; ?>