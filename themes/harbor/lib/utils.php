<?php

use Essentials\Data\Options,
    Essentials\Html\Writer;

/**
 * Check Essentials activated
 */
function om_is_essentials()
{
    static $essentials_active;

    if (!isset($essentials_active)) {
        $essentials_active = defined('OM_COLORS_CREATIVE_ESSENTIALS')
                             && version_compare(OM_COLORS_CREATIVE_ESSENTIALS, '1.1.0', '>=')
                             && function_exists('openmarco_essentials_init')
                             && class_exists('TitanFramework');
    }

    return $essentials_active;
}

/**
 * Check if Visual Composer is activated
 *
 * @return bool
 */
function om_is_vc()
{
    return defined('WPB_VC_VERSION');
}

/**
 * Check Visual Composer enabled on current or specific page
 *
 * @param null $post_id
 * @return bool
 */
function om_is_vc_page($post_id = null)
{
    if (!om_is_vc()) {
        return false;
    }

    static $is_vc;

    if (!$is_vc) {
        $post = get_post($post_id);
        $is_vc = $post && (strpos($post->post_content, 'vc_row') !== false);
    }

    return $is_vc;
}

/**
 * Check if WooCommerce is activated
 *
 * @return bool
 */
function om_is_wc()
{
    return class_exists('WooCommerce');
}

/**
 * Check if WooCommerce navigation menu cart enabled on current page
 *
 * @return bool
 */
function om_is_wc_nav_cart()
{
    if (!om_is_wc() || !Options::get('wc_cart_nav_enabled') || null === get_post(wc_get_page_id('cart'))) {
        return false;
    }

    return Options::get('wc_cart_nav_shop_only') ? (is_shop() || is_cart() || is_product() || is_product_category() || is_product_tag() || is_product_taxonomy() || is_account_page()) : true;
}

/**
 * Check if current page is WooCommerce page
 *
 * @return bool
 */
function om_is_wc_page()
{
    return om_is_wc() && (is_cart() || is_checkout() || is_account_page());
}

/**
 * Check if Master Slider is activated
 *
 * @return bool
 */
function om_is_ms()
{
    return defined('MSWP_AVERTA_VERSION');
}

/**
 * Check if WPML is activated
 *
 * @return bool
 */
function om_is_wpml()
{
    return defined('ICL_SITEPRESS_VERSION') && !((bool)get_option('_wpml_inactive') === true);
}

/**
 * Retrieves ID for the current page
 *
 * @return bool|int|mixed|void Current page id
 */
function om_get_current_page_id()
{
    if (is_post_type_archive('product') || is_tax(array('product_cat', 'product_tag'))) {
        $id = get_option('woocommerce_shop_page_id');
    } else if (is_home() || is_category() || is_tag() || is_tax() || is_author()) {
        $id = get_option('page_for_posts');
    } else if (is_404()) {
        $id = 0;
    } else {
        $id = get_the_ID();
    }

    return $id;
}


function om_get_body_class()
{
    $class = '';

    $page_id = om_get_current_page_id();

    if ($page_id && om_is_essentials()) {
        $background_color = Options::get('body_background_color_current', $page_id);

        if (!empty($background_color)) {
            $class = om_is_dark($background_color) ? 'background-dark' : 'background-light';
        }
    }

    return $class;
}

/**
 * Render link to all posts in page navigation
 */
function om_all_posts_link()
{
    global $post;

    if (!isset($post->post_type)) {
        return;
    }

    $page_id = $post->post_type == 'project' ? Options::get('projects_link_page') : get_option('page_for_posts');

    if (!empty($page_id)) {
        $url = get_permalink($page_id);
    }

    if (empty($url)) {
        $url = get_post_type_archive_link($post->post_type);
    }

    $url = apply_filters('om_all_posts_link_url', $url, $post->post_type);

    if (!empty($url)) {
        echo apply_filters('om_all_posts_link', '<li class="all"><a href="' . esc_url($url) . '"><span class="icon ion-grid"></span></a></li>', $post->post_type);
    }
}

/**
 * Render link to previous post in page navigation
 */
function om_prev_post_link()
{
    global $post;

    if (!isset($post->post_type)) {
        return;
    }

    $args = apply_filters('om_prev_post_link', array(
        'format' => '<li class="previous">%link</li>',
        'link' => '<span class="arrow-left" aria-hidden="true"></span> <span class="hidden-xs">%title</span>',
        'in_same_term' => false,
        'excluded_terms' => '',
        'taxonomy' => 'category'
    ));

    call_user_func_array('previous_post_link', $args);
}

/**
 * Render link to next post in page navigation
 */
function om_next_post_link()
{
    global $post;

    if (!isset($post->post_type)) {
        return;
    }

    $args = apply_filters('om_next_post_link', array(
        'format' => '<li class="next">%link</li>',
        'link' => '<span class="hidden-xs">%title</span> <span class="arrow-right" aria-hidden="true"></span>',
        'in_same_term' => false,
        'excluded_terms' => '',
        'taxonomy' => 'category'
    ));

    call_user_func_array('next_post_link', $args);
}

/**
 * Retrieves categories list for given a post ID or post object.
 *
 * @param int|WP_Post $post Optional. Post ID or post object. Defaults to global $post.
 * @return array|null List of categories url => name
 */
function om_get_categories_list($post = null)
{
    $post = get_post($post);
    $type = $post ? $post->post_type : '';

    $taxonomies = array(
        'post' => 'category',
        'project' => 'project_category'
    );

    if (array_key_exists($type, $taxonomies) ) {

        $categories = get_the_terms($post, $taxonomies[$type]);

        if (!empty($categories)) {
            $list = array();

            foreach ($categories as $category) {
                $list[get_term_link($category->term_id, $category->taxonomy)] = $category->name;
            }

            return $list;
        }
    }

    return null;
}

/**
 * Retrieves categories names for given a post ID or post object
 *
 * @param null $post Optional. Post ID or post object. Defaults to global $post.
 * @return string Categories names as string
 */
function om_get_categories_names($post = null)
{
    $categories = om_get_categories_list($post);

    return empty($categories) ? '' : implode(', ', $categories);
}

/**
 * Retrieves categories list for current post
 *
 * @param string $before Optional. Content to prepend to the title.
 * @param string $after Optional. Content to append to the title.
 * @return string Categories names as string
 */
function om_categories_links($before = '', $after = '')
{
    $categories = om_get_categories_list();

    if (!empty($categories)) {
        om_html($before);

        $first = true;

        foreach ($categories as $link => $name) {
            if ($first) {
                $first = false;
            } else {
                echo ', ';
            }

            echo '<a href="', esc_url($link), '">', esc_html($name), '</a>';
        }

        om_html($after);
    }
}

function om_get_header_description($page_id)
{
    if (is_tax() || is_tag() || is_category()) {
        global $term, $taxonomy, $tag_id, $cat;

        $current = is_tag()
            ? get_tag($tag_id)
            : (is_category() ? get_category($cat) : get_term_by('slug', $term, $taxonomy));

        $text = $current->description;
    } elseif (is_author()) {
        $text = get_the_author_meta('description');
    } else {
        $text = !om_is_essentials() ? '' : Options::get('header_text', $page_id);
    }
    return wpautop($text);
}

function om_page_nav($section_atts)
{
    global $page, $numpages, $multipage, $more;

    if ($multipage) {
        echo '<nav class="container page-nav" ', $section_atts, '><ul class="pagination pagination-sm">';

        for ($index = 1; $index <= $numpages; $index++) {
            $active = !($index != $page || !$more && 1 == $page);

            echo '<li', ($active ? ' class="active"' : ''), '>';
            echo($active ? "<span>{$index}</span>" : _wp_link_page($index) . $index . '</a>');
            echo '</li>';
        }

        echo '</ul></nav>';
    }
}

function om_add_responsive_image_attributes($attr, $id, $sizes)
{
    if (!empty($id)) {
        $mime = get_post_mime_type($id);

        if ($mime !== 'image/gif') {
            $attr['class'] = esc_attr(trim((isset($attr['class']) ? $attr['class'] : '') . ' lazyload'));

            $attr['data-sizes'] = 'auto';

            $src_set = array();

            foreach ($sizes as $size) {
                $src = wp_get_attachment_image_src($id, $size);

                // Jetpack Photon service removes image size, need to restore it from url:
                if (empty($src[1])) {
                    $location = parse_url($src[0]);

                    if (isset($location['query'])) {
                        $query = array();
                        parse_str($location['query'], $query);

                        if (isset($query['w'])) {
                            $src[1] = $query['w'];
                        }
                    }
                }

                $src_set[] = esc_url($src[0]) . ' ' . esc_attr($src[1]) . 'w';
            }

            $attr['data-srcset'] = implode(',', $src_set);
        }
    }

    return $attr;
}

function om_responsive_thumbnail($post_id, array $sizes = array(), $attr = array())
{
    $id = get_post_thumbnail_id($post_id);

    $attr = om_add_responsive_image_attributes($attr, $id, $sizes);

    $mime = get_post_mime_type($id);

    if ($mime === 'image/gif') {
        $sizes[0] = 'full';
    }

    echo get_the_post_thumbnail($post_id, $sizes[0], $attr);
}

function om_responsive_image($id, $sizes = array(), $attr = array())
{
    $attr = om_add_responsive_image_attributes($attr, $id, $sizes);

    if (!empty($id)) {
        $mime = get_post_mime_type($id);

        if ($mime == 'image/gif') {
            $sizes[0] = 'full';
        }

        $img = wp_get_attachment_image_src($id, $sizes[0]);

        $attr['src'] = esc_url($img[0]);
        $attr['alt'] = esc_attr(om_get_image_alt($id));

        echo Writer::init()->img($attr, true);
    }
}

function om_get_image_sizes($size = '')
{
    global $_wp_additional_image_sizes;

    $sizes = array();
    $get_intermediate_image_sizes = get_intermediate_image_sizes();

    // Create the full array with sizes and crop info
    foreach ($get_intermediate_image_sizes as $_size) {

        if (in_array($_size, array('thumbnail', 'medium', 'large'), true)) {

            $sizes[$_size]['width'] = get_option($_size . '_size_w');
            $sizes[$_size]['height'] = get_option($_size . '_size_h');
            $sizes[$_size]['crop'] = (bool)get_option($_size . '_crop');

        } elseif (isset($_wp_additional_image_sizes[$_size])) {

            $sizes[$_size] = array(
                'width' => $_wp_additional_image_sizes[$_size]['width'],
                'height' => $_wp_additional_image_sizes[$_size]['height'],
                'crop' => $_wp_additional_image_sizes[$_size]['crop']
            );
        }
    }

    // Get only 1 size if found
    if ($size) {
        if (isset($sizes[$size])) {
            return $sizes[$size];
        } else {
            return false;
        }
    }

    return $sizes;
}

function om_get_image_sizes_list()
{
    return from(om_get_image_sizes())->toDictionary('om_get_image_sizes_list_name', '$k')->toArray();
}

function om_get_image_sizes_list_name($size, $name)
{
    if ($size['crop']) {
        $params = "width: {$size['width']}, height: {$size['height']}";
    } else if ($size['height'] == 0) {
        $params = "max width: {$size['width']}";
    } else if ($size['width'] >= 9999) {
        $params = "max height: {$size['height']}";
    } else {
        $params = "max width: {$size['width']}, max height: {$size['height']}";
    }

    $name = ucwords(str_replace('-', ' ', $name));

    return "{$name} - {$params}";
}

/**
 * Get image alt value
 *
 * @param int $id Image attachment ID.
 * @return string
 */
function om_get_image_alt($id)
{
    $alt = trim(strip_tags(get_post_meta($id, '_wp_attachment_image_alt', true)));

    if (empty($alt)) {
        $info = get_post($id);

        $alt = trim(strip_tags($info->post_excerpt));

        if (empty($alt)) {
            $alt = trim(strip_tags($info->post_title));
        }
    }

    return $alt;
}

function om_get_image_lightbox_caption($id)
{
    $attachment = get_post($id);

    $caption = '';

    if ($attachment) {
        $caption = $attachment->post_title;

        if (!empty($attachment->post_excerpt)) {
            $caption = empty($caption)
                ? $attachment->post_excerpt
                : ("$caption<br/><small>{$attachment->post_excerpt}</small>");
        }
    }

    return $caption;
}

/**
 * Retrieves image info for given image sizes
 *
 * @param int $id Image attachment ID.
 * @param string $sizes
 * @return array
 */
function om_get_image($id, $sizes = 'full')
{
    if (is_string($sizes) || (is_array($sizes) && absint($sizes[0]) && absint($sizes[1]))) {
        $sizes = array($sizes);
    }

    $image = array(
        'alt' => om_get_image_alt($id)
    );

    foreach ($sizes as $size) {
        $data = wp_get_attachment_image_src($id, $size);

        if (is_array($size)) {
            $size = $size[0] . 'x' . $size[1];
        }

        $image[$size] = array(
            'url' => $data[0],
            'width' => $data[1],
            'height' => $data[2],
        );
    }

    return $image;
}

/**
 * Retrieves shifting attributes for section
 *
 * @param string $section_color
 * @param null $id
 * @param int $navmenu_transparency
 * @return array List of attributes
 */
function om_get_shifting_attributes($section_color, $id = null, $navmenu_transparency = 1)
{
    $attributes = array();

    if (om_is_essentials()) {
        $background_color_behavior = Options::get('background_color_behavior_current', $id);

        if (empty($section_color)) {
            $section_color = Options::get('body_background_color_current', $id);

            if (empty($section_color)) {
                $defined = om_get_theme_defined();
                $section_color = $defined['background'];
            }
        }

        if (empty($section_color)) {
            return $attributes;
        }

        if (is_array($section_color)) {
            $section_color = implode(';', $section_color);
        }

        $navigation_transparency = Options::get('navigation_header_overlay_background_transparency', $id);
        $navigation_by_section = Options::get('navigation_header_overlay_by_section_current', $id);
        $navigation_opened_by_section = Options::get('navigation_nav_by_section_current', $id);
        $navigation_secondary_by_section = Options::get('navigation_nav_secondary_by_section', $id);

        if ($background_color_behavior == 'shifting'
            || $navigation_by_section
            || $navigation_opened_by_section
        ) {

            $attributes['data-scroll-bg'] = $section_color;
            $bg_target = array();
            $bg_alpha = array();

            if ($background_color_behavior == 'shifting') {
                $bg_target[] = 'body';
                $bg_alpha[] = 1;
            } else {
                $attributes['data-scroll-child-target'] = '>.section-background-main';
                $attributes['data-scroll-hook'] = 0;
                $attributes['data-scroll-offset'] = $navigation_transparency < 0.2 ? 'halfheight' : 'height';
            }

            if ($navigation_by_section) {
                $bg_target[] = '.navmenu-header-overlay';
                $bg_alpha[] = esc_attr($navmenu_transparency);
            }

            if ($navigation_opened_by_section) {
                $bg_target[] = '.navmenu-nav .nav-background-overlay';
                $bg_alpha[] = 1;
            }

            if ($navigation_secondary_by_section) {
                $bg_target[] = '.navmenu-nav .dropdown-menu';
                $bg_alpha[] = 1;
            }

            $attributes['data-scroll-target'] = implode(',', $bg_target);

            if ($navmenu_transparency != 1) {
                $attributes['data-scroll-alpha'] = '[' . implode(',', $bg_alpha) . ']';
            }
        }
    }

    return $attributes;
}

/**
 * Check if color dark or light
 *
 * @param string $color
 * @return bool
 */
function om_is_dark($color)
{
    $color = Primal\Color\Parser::Parse($color);
    return ($color->red * 299 + $color->green * 587 + $color->blue * 114) / 1000 < 125;
}

function om_color_darken($color, $value)
{
    if (empty($color)) {
        return null;
    }

    $color = Primal\Color\Parser::Parse($color);
    $color = $color->toHSL();
    $color->luminance = max($color->luminance - $value, 0);
    return $color->toRGB()->toHex();
}

function om_custom_title($title)
{
    // replace newline characters with tags
    return str_replace(array("\r\n", "\r", "\n"), '<br/>', $title);
}

/**
 * Render title and subtitle
 */
function om_title()
{
    $page_id = om_get_current_page_id();

    if ($page_id && om_is_essentials() && !is_category() && !is_tag() && !is_tax()) {

    $title = apply_filters('om_custom_title', Options::get('header_title', $page_id));
    $subtitle = apply_filters('om_custom_subtitle', Options::get('header_subtitle', $page_id));
}

    if (empty($title)) {
        $posts_page_id = get_option('page_for_posts', true);

        if (is_home()) {
            if ($posts_page_id) {
                $title = get_the_title($posts_page_id);
            } else {
                $title = esc_html__('Latest Posts', 'colors-creative');
            }
        } elseif (is_post_type_archive('product')) {
            $title = woocommerce_page_title(false);
        } elseif (is_archive()) {
            $title = get_the_archive_title();
        } elseif (is_404()) {
            $title = esc_html__('Not Found', 'colors-creative');
        } else {
            the_title('<h1 class="title entry-title">', '</h1>');
        }
    }

    if (!empty($title)) {
        echo '<h1 class="title entry-title">', balanceTags(wp_kses_post($title), true), '</h1>';
    }

    if (!empty($subtitle)) {
        echo '<div class="subtitle h3">', balanceTags(wp_kses_post($subtitle), true), '</div>';
    }
}

/**
 * Check comments section render
 */
function om_has_comments_section()
{
    return !post_password_required() && (have_comments() || comments_open() || (!comments_open() && get_comments_number() != '0' && post_type_supports(get_post_type(), 'comments')));
}

/**
 * Render html string content
 *
 * @param $content string String content to output
 * @param bool $balance Balances tags option
 */
function om_html($content, $balance = false)
{
    if ($balance) {
        $content = balanceTags($content, true);
    }

    echo htmlspecialchars_decode(esc_html($content));
}

/**
 * Convert attributes list to string
 *
 * @param $attributes array List of attributes, keys is attributes' names
 * @return string attributes as string
 */
function om_get_attributes_string($attributes)
{
    if (!is_array($attributes)) {
        return '';
    }

    $result = '';

    foreach ($attributes as $name => $value) {
        $result .= ' ' . esc_attr($name) . '="' . esc_attr($value) . '"';
    }

    return $result;
}

/**
 * Print attributes list
 *
 * @param $attributes array List of attributes, keys is attributes' names
 */
function om_attributes_string($attributes)
{
    echo om_get_attributes_string($attributes);
}

/**
 * Get splash background color
 *
 * @return bool|mixed|null|string
 */
function om_get_splash_background()
{
    $color = false;

    if (om_is_essentials()) {
        $color = Options::get('splash_background_color');

        if (empty($color)) {
            $color = Options::get('body_background_color');
        }
    }

    return $color;
}

/**
 * Escaping for styles content attributes.
 *
 * @param string $css
 * @return string
 */
function om_esc_styles($css)
{
    return str_replace('<', '', $css);
}