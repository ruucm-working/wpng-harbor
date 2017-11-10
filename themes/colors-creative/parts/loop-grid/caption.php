<?php
$type = get_post_type();
?>

<div class="caption">
    <div class="caption-content">
        <div class="background background-mobile hidden-md hidden-lg"></div>
        <div class="background background-overlay hidden-xs hidden-sm"></div>
        <div class="background background-rest animate hover-fade hidden-xs hidden-sm"></div>
        <div class="background background-hover animate rest-fade hidden-xs hidden-sm"></div>
        <div class="top">
            <div class="entry-meta<?php if($type === 'project') echo ' sr-only'; ?>">
                <?php get_template_part('parts/entry-meta'); ?>
            </div>

            <h3 class="entry-title">
                <a class="animate" href="<?php the_permalink() ?>" title="<?php esc_attr(get_the_title()) ?>">
                    <?php the_title(); ?>
                </a>
            </h3>
        </div>
        <div class="bottom">
            <a href="<?php the_permalink() ?>" title="<?php esc_attr(get_the_title()) ?>"
               class="btn btn-xs btn-primary btn-flat btn-read">
                <?php $type === 'project' ? esc_html_e('More', 'colors-creative') : esc_html_e('Read', 'colors-creative') ?>
                <span class="arrow-right"></span>
            </a>
        </div>
    </div>
</div>