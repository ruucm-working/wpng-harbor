<?php

add_filter('excerpt_more', function () {
    return '&hellip;';
});

/* removed since 1.4.5.3
add_filter('get_the_excerpt', function ($content) {
    global $post;

    if(is_feed()) {
        return $content;
    }

    $link = get_permalink($post);

    if (preg_match('/<!--more(.*?)?-->/', $post->post_content, $matches)) {
        $link .= "#more-{$post->ID}";

        if (!empty($matches[1])) {
            $text = strip_tags(wp_kses_no_null(trim($matches[1])));
        }
    }

    if (!isset($text)) {
        if(is_search()) {
            $text = __('Continue', 'colors-creative');
        } else {
            $text = __('Read more', 'colors-creative');
        }
    }

    return $content . ('<p><a href="' . esc_url($link) . '">' . esc_html($text) . ' <span class="arrow-right"></span></a></p>');
});
*/

add_filter('the_password_form', function ($form) {
    $search = array(
        'class="post-password-form"',
        'name="post_password"',
        'name="Submit"'
    );

    $replace = array(
        'class="post-password-form form-inline"',
        'name="post_password" class="form-control"',
        'name="Submit" class="btn btn-primary"'
    );

    $search = str_replace($search, $replace, $form);

    if (om_is_vc_page()) {
        $search =
            '<div class="section"><div class="container"><div class="section-content"><div class="row"><div class="col-sm-10 col-sm-offset-1 text-center">'
            . $search
            . '</div></div></div></div></div>';
    }

    return $search;
});