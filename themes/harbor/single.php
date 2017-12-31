<?php use Essentials\Data\Options; ?>
<?php
if (!om_is_essentials()) {
    $section_class = '';
    $show_meta = true;
    $next_prev_container_class = 'container';
} else {
    $section_class = Options::get('content_section_size');
    $show_meta = (get_post_type() === 'post' && Options::get('post_meta_data_show'))
        || (get_post_type() === 'post' && Options::get('post_meta_data_show'));
    $next_prev_container_class = Options::specified('single_next_previous_container') ? Options::get('single_next_previous_container') : 'container';

}
?>

<?php while (have_posts()) : the_post(); ?>
    <!-- Facebook Init -->
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = 'https://connect.facebook.net/ko_KR/sdk.js#xfbml=1&version=v2.11&appId=435773580170443';
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
    <?php $section_atts = om_get_shifting_attributes(''); ?>
    <article <?php post_class(); ?>>
        <?php get_template_part('parts/header'); ?>
        <div class="entry-content">
            <?php if (om_is_vc_page()) : ?>
                <?php the_content(); ?>
            <?php else: ?>
                <div class="section <?php echo esc_attr($section_class) ?>"<?php om_attributes_string($section_atts) ?>>
                    <div class="container">
                        <div class="section-content">
                            <div class="row">
                                <div class="col-sm-10 col-sm-offset-1">
                                    <?php the_content(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif ?>
        </div>

        <?php if ($show_meta) : ?>
            <div class="section section-half"<?php om_attributes_string($section_atts) ?>>
                <div class="container">
                    <div class="section-content">
                        <?php if (in_array(get_post_type(), array('post', 'project'), true)) get_template_part('parts/' . get_post_type() . '-meta'); ?>
                    </div>
                </div>
            </div>
        <?php endif ?>

        <?php if (!om_is_vc_page()) om_page_nav(om_get_attributes_string($section_atts)); ?>

        <?php if (om_has_comments_section()) : ?>
            <div class="container"<?php om_attributes_string($section_atts) ?>>
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        <?php comments_template('/parts/comments.php'); ?>
                    </div>
                </div>
            </div>
        <?php endif ?>


        <nav class="<?php echo sanitize_html_class($next_prev_container_class) ?>"<?php om_attributes_string($section_atts) ?>>
            <ul class="pager">
                <?php om_all_posts_link(); ?>
                <?php om_prev_post_link(); ?>
                <?php om_next_post_link(); ?>
            </ul>
        </nav>
    </article>
    <div class="fb-comments" data-href="http://harbor.cz/projects/<?php echo $post->post_name; ?>" data-colorscheme="light" data-num-posts="4" data-width="706"></div>
<?php endwhile;