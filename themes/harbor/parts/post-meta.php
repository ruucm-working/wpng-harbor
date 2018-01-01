<?php
use Essentials\Data\Options;

if (!om_is_essentials()) {
    $show_author = true;
    $show_date = true;
    $show_categories = true;
    $show_tags = true;
} else {
    $show_author = Options::get('post_author_show');
    $show_date = Options::get('post_date_show');
    $show_categories = Options::get('post_categories_show');
    $show_tags = Options::get('post_tags_show');
}
?>

<div class="entry-single-meta">
    <div class="row">
        <div class="col-sm-offset-1 <?php if ($show_date) : ?>col-sm-5 col-md-6 col-lg-7<?php else : ?>col-sm-10<?php endif ?>">
            <div class="entry-info">
                <?php if ($show_author) : ?>
                    <span class="byline author vcard">
                        <?php esc_html_e('By', 'colors-creative') ?>
                        <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" rel="author"
                           class="fn"><?php echo get_the_author(); ?></a>
                    </span>
                <?php endif ?>

                <?php if ($show_categories) : ?>
                    <?php
                    $categories_in = esc_html__('in', 'colors-creative');
                    if(!$show_author) $categories_in = ucfirst($categories_in);
                    ?>
                    <?php om_categories_links('<span class="entry-categories">' . $categories_in . ' ', '</span>') ?>
                <?php endif ?>
            </div>
        </div>
        <?php if ($show_date) : ?>
        <div class="col-sm-5 col-md-4 col-lg-3">
            <time class="updated text-muted" datetime="<?php echo esc_attr(get_the_time('c')) ?>">
                <?php echo get_the_date() ?>
            </time>
        </div>
        <?php endif ?>


        <?php if ($show_tags) : ?>
            <?php the_tags('<div class="clearfix"></div><div class="col-sm-10 col-sm-offset-1"><div class="entry-tags"><span class="ion-pound"></span> ', ', ', '</div></div>'); ?>
        <?php endif ?>
    </div>
</div>