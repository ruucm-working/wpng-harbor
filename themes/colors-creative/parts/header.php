<?php

use Essentials\Data\Options;

$id = om_get_current_page_id();

if (!$id || !om_is_essentials()) {
    $display = '';
    $header_type = 'standard';
    $section_class = '';
    $section_atts = '';
    $is_background = false;
    $is_image = false;
    $is_overlay = false;
    $is_parallax = false;
    $header_height = false;
    $content_vertical = 'middle';
    $content_horizontal = 'center';
    $show_title = true;
} else {
    $display = Options::get('header_display_current', $id);
    $header_type = Options::get('header_type', $id);
    $header_height = Options::get('header_height', $id);
    $background_color_behavior = Options::get('background_color_behavior_current', $id);
    $navmenu_transparency = Options::get('header_navigation_bar_transparency', $id);
    $is_background = Options::specified('header_background_color', $id) && $background_color_behavior === 'static';

    $section_color = Options::get('header_background_color', $id);
    $section_class = Options::get('header_section_show_overflow', $id) ? '' : ' section-inside';

    if (!empty($section_color) && $background_color_behavior === 'static') {
        $section_class .= ' background-' . (om_is_dark($section_color) ? 'dark' : 'light');
    }

    $section_atts = om_get_shifting_attributes(array_filter(array(
        esc_attr($section_color),
        esc_attr(Options::get('header_background_color_2', $id)),
        esc_attr(Options::get('header_background_color_3', $id)),
        esc_attr(Options::get('header_background_color_4', $id)),
        esc_attr(Options::get('header_background_color_5', $id)),
        esc_attr(Options::get('header_background_color_6', $id)),
        esc_attr(Options::get('header_background_color_7', $id)),
        esc_attr(Options::get('header_background_color_8', $id)),
        esc_attr(Options::get('header_background_color_9', $id)),
        esc_attr(Options::get('header_background_color_10', $id))
    )), $id, $navmenu_transparency);

    $content_horizontal = Options::specified('header_content_h_alignment', $id) ? Options::get('header_content_h_alignment', $id) : 'left';
    $content_vertical = Options::specified('header_content_v_alignment', $id) ? Options::get('header_content_v_alignment', $id) : 'middle';

    $show_title = !Options::get('header_title_hide', $id);
    $is_image = Options::specified('header_image', $id);
    $is_overlay = Options::specified('header_overlay_color', $id);
    $is_parallax = Options::get('header_parallax', $id);

    if(!$is_image && Options::get('header_use_featured_image_current', $id)) {
        $header_image_id = get_post_thumbnail_id($id);
        $is_image = !empty($header_image_id);
    }
}

$text = om_get_header_description($id);

if (!empty($text) && is_string($text)) {
    $text = do_shortcode($text);
}

$header_classes = array('entry-header');
if ($display === 'invisible') {
    $header_classes[] = 'sr-only';
}
if ($header_type === 'alternate') {
    $header_classes[] = 'alt';
}
$header_classes = implode(' ', $header_classes);

?>

<?php if ($display !== 'none') : ?>
    <?php do_action('om_theme_before_page_header'); ?>

    <header class="<?php echo esc_attr($header_classes) ?>">
        <?php if ($header_type === 'standard' || $header_type === 'standard-fluid' || empty($header_type)) : ?>
            <?php do_action('om_theme_before_page_header_standard_section'); ?>
            <div
                class="section section-header<?php echo esc_attr($section_class) ?>"<?php om_attributes_string($section_atts) ?>>
                <?php if ($is_background) : ?>
                    <div class="section-background section-background-main"></div>
                <?php endif ?>
                <?php if ($is_image) : ?>
                    <div class="section-background section-background-image"
                        <?php if ($is_parallax) : ?>
                            data-scroll-trigger=".section"
                            data-scroll-animate="transform:translate3d(0,$1%,0)"
                            data-0_100="0"
                            data-100_100="40"
                        <?php endif ?>></div>
                <?php endif ?>
                <?php if ($is_overlay) : ?>
                    <div class="section-background section-background-overlay"
                        <?php if ($is_parallax) : ?>
                            data-scroll-trigger=".section"
                            data-scroll-animate="transform:translate3d(0,$1%,0)"
                            data-0_100="0"
                            data-100_100="40"
                        <?php endif ?>></div>
                <?php endif ?>

                <div class="section-flex"
                    <?php if ($header_height) echo 'data-vhmin="', esc_attr($header_height), '" data-vhmin-offset=".navmenu"' ?>>
                    <div class="section-flex-<?php echo sanitize_html_class($content_vertical) ?>">
                        <div class="container<?php if($header_type === 'standard-fluid') echo '-fluid'?>">
                            <div class="section-content">
                                <div class="text-<?php echo sanitize_html_class($content_horizontal) ?>"
                                    <?php if ($is_parallax) : ?>
                                        data-scroll-trigger=".section"
                                        data-scroll-animate="transform:translateY($1px);opacity"
                                        data-0_100="0;1"
                                        data-70_100="210;0"
                                        data-100_100="300;0"
                                    <?php endif ?>>

                                    <?php if ($show_title) om_title(); ?>

                                    <?php if (!empty($text)) : ?>
                                        <div class="description"
                                            <?php if ($is_parallax) : ?>
                                                data-scroll-trigger=".section"
                                                data-scroll-animate="transform:translateY($1px)"
                                                data-0_100="0"
                                                data-70_100="30"
                                            <?php endif ?>>
                                            <?php om_html($text, true) ?>
                                        </div>
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php do_action('om_theme_after_page_header_standard_section'); ?>
        <?php else : ?>
            <?php do_action('om_theme_before_page_header_alternate_section'); ?>
            <div class="section section-header section-alt<?php echo esc_attr($section_class) ?>"<?php om_attributes_string($section_atts) ?>>
                <?php if ($is_background) : ?>
                    <div class="section-background section-background-main"></div>
                <?php endif ?>
                <div class="container">
                    <div class="section-content">
                        <div class="row">
                            <div class="col-sm-7 col-lg-6">
                                <?php if ($is_image) : ?>
                                    <div class="image-alt"
                                        <?php if ($is_parallax) : ?>
                                            data-scroll-trigger=".section"
                                            data-scroll-animate="transform:translateY($1%)"
                                            data-0_100="0"
                                            data-100_100="-80"
                                        <?php endif ?>>
                                        <?php if ($is_overlay) : ?>
                                            <div class="section-background-overlay"></div>
                                        <?php endif ?>
                                        <?php
                                        om_responsive_image(
                                            Options::get('header_image', $id),
                                            array('large-width', 'extra-large-width'),
                                            array('class' => 'img-responsive'))
                                        ?>
                                    </div>
                                <?php endif ?>
                            </div>
                            <div class="col-sm-5 col-lg-6 text-<?php echo sanitize_html_class($content_horizontal) ?>"
                                <?php if ($is_parallax) : ?>
                                    data-scroll-trigger=".section"
                                    data-scroll-animate="transform:translateY($1%)"
                                    data-0_100="0"
                                    data-100_100="30"
                                <?php endif ?>>

                                <?php if ($show_title) om_title(); ?>

                                <?php if (!empty($text)) : ?>
                                    <div class="description">
                                        <?php om_html($text, true) ?>
                                    </div>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php do_action('om_theme_after_page_header_alternate_section'); ?>
        <?php endif ?>
    </header>

    <?php do_action('om_theme_after_page_header'); ?>
<?php endif;