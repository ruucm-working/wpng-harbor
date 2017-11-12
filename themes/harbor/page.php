<?php use Essentials\Data\Options; ?>
<?php
if (!om_is_essentials()) {
    $section_class = '';
} else {
    $section_class = Options::get('content_section_size');
}
?>

<?php while (have_posts()) : the_post(); ?>
    <?php $section_atts = om_get_shifting_attributes(''); ?>
    <article <?php post_class(); ?>>
        <?php get_template_part('parts/header'); ?>

        <div class="entry-meta sr-only">
            <?php get_template_part('parts/entry-meta'); ?>
        </div>

        <div class="entry-content">
            <?php if (om_is_vc_page()): ?>
                <?php the_content(); ?>
            <?php else : ?>
                <div class="section <?php echo esc_attr($section_class) ?>"<?php om_attributes_string($section_atts) ?>>
                    <div class="container">
                        <div class="section-content">
                            <?php if (om_is_wc_page()): ?>
                                <?php the_content(); ?>
                            <?php else : ?>
                                <div class="row">
                                    <div class="col-sm-10 col-sm-offset-1">
                                        <?php the_content(); ?>
                                    </div>
                                </div>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            <?php endif ?>
        </div>

        <?php wp_link_pages(array('before' => '<nav class=\"pagination\"' . om_get_attributes_string($section_atts) . '>', 'after' => '</nav>')); ?>

        <?php if (om_has_comments_section()) : ?>
            <div class="container"<?php om_attributes_string($section_atts) ?>>
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        <?php comments_template('/parts/comments.php'); ?>
                    </div>
                </div>
            </div>
        <?php endif ?>
    </article>
<?php endwhile;