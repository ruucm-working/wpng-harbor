<?php
if (post_password_required()) {
    return;
}
?>

<?php do_action('om_theme_before_comments'); ?>

<section id="comments" class="comments<?php if(get_option('show_avatars')) echo ' comments-avatars' ?>">
    <?php if (have_comments()) : ?>
        <h2 class="comments-header"><?php printf(_nx('One response', '%1$s responses', get_comments_number(), 'comments title', 'colors-creative'), number_format_i18n(get_comments_number())) ?></h2>

        <ol class="comment-list">
            <?php wp_list_comments(array('style' => 'ol', 'short_ping' => true, 'avatar_size' => 64)) ?>
        </ol>

        <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
            <nav>
                <ul class="pager">
                    <?php if (get_previous_comments_link()) : ?>
                        <li class="previous"><span class="arrow-left"></span> <?php previous_comments_link(esc_html__('Older comments', 'colors-creative')) ?></li>
                    <?php endif ?>
                    <?php if (get_next_comments_link()) : ?>
                        <li class="next"><?php next_comments_link(esc_html__('Newer comments', 'colors-creative')) ?> <span class="arrow-right"></span></li>
                    <?php endif ?>
                </ul>
            </nav>
        <?php endif ?>
    <?php endif ?>

    <?php if (!comments_open() && get_comments_number() != '0' && post_type_supports(get_post_type(), 'comments')) : ?>
        <div class="alert alert-warning">
            <?php esc_html_e('Comments are closed.', 'colors-creative') ?>
        </div>
    <?php endif ?>

    <?php comment_form() ?>
</section>

<?php do_action('om_theme_after_comments'); ?>