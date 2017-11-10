<time class="updated" datetime="<?php echo esc_attr(get_the_time('c')) ?>"><?php echo get_the_date() ?></time>
<span class="byline author vcard sr-only"><a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" rel="author" class="fn"><?php echo get_the_author(); ?></a></span>
<?php if(get_post_type() === 'post') : ?>
<?php om_categories_links(esc_html__('in', 'colors-creative') . ' ') ?>
<?php endif;
