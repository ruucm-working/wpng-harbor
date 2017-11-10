<?php

add_editor_style('assets/css/editor.css');

add_filter('tiny_mce_before_init', function ($mceInit) {

    if (isset($mceInit['formats'])) {
        $mceInit['formats'] = str_replace(
            array("styles: {textAlign:'left'}", "styles: {textAlign:'center'}", "styles: {textAlign:'right'}"),
            array("classes: 'text-left'", "classes: 'text-center'", "classes: 'text-right'"),
            $mceInit['formats']
        );

        $mceInit['formats'] = substr_replace($mceInit['formats'],
            "alignjustify:[{selector:'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li',classes:'text-justify'}],",
            1, 0);
    }

    if (isset($mceInit['toolbar2'])) {
        $mceInit['toolbar2'] = str_replace('formatselect', 'formatselect,styleselect', $mceInit['toolbar2']);

        $mceInit['style_formats'] = json_encode(array(
            array(
                'title' => 'Inline',
                'items' => array(
                    array(
                        'title' => esc_html__('Small', 'colors-creative'),
                        'inline' => 'small',
                    ),
                    array(
                        'title' => esc_html__('Marked', 'colors-creative'),
                        'inline' => 'mark',
                    ),
                    array(
                        'title' => esc_html__('Uppercase', 'colors-creative'),
                        'inline' => 'span',
                        'classes' => 'text-uppercase'
                    ),
                    array(
                        'title' => esc_html__('Lowercase', 'colors-creative'),
                        'inline' => 'span',
                        'classes' => 'text-lowercase'
                    ),
                    array(
                        'title' => esc_html__('Capitalize', 'colors-creative'),
                        'inline' => 'span',
                        'classes' => 'text-capitalize'
                    ),
                ),
            ),
            array(
                'title' => 'Lists',
                'items' => array(
                    array(
                        'title' => esc_html__('Left-shifted', 'colors-creative'),
                        'selector' => 'ul,ol',
                        'classes' => 'list-left-shifted',
                    ),
                    array(
                        'title' => esc_html__('Unstyled', 'colors-creative'),
                        'selector' => 'ul,ol',
                        'classes' => 'list-unstyled',
                    ),
                    array(
                        'title' => esc_html__('Inline', 'colors-creative'),
                        'selector' => 'ul,ol',
                        'classes' => 'inline',
                    ),
                ),
            ),
            array(
                'title' => 'Blocks',
                'items' => array(
                    array(
                        'title' => esc_html__('Lead', 'colors-creative'),
                        'block' => 'p',
                        'classes' => 'lead',
                    ),
                    array(
                        'title' => esc_html__('Heading 1 view', 'colors-creative'),
                        'selector' => 'p,h1,h2,h3,h4,h5,h6,div',
                        'classes' => 'h1',
                    ),
                    array(
                        'title' => esc_html__('Heading 2 view', 'colors-creative'),
                        'selector' => 'p,h1,h2,h3,h4,h5,h6,div',
                        'classes' => 'h2',
                    ),
                    array(
                        'title' => esc_html__('Heading 3 view', 'colors-creative'),
                        'selector' => 'p,h1,h2,h3,h4,h5,h6,div',
                        'classes' => 'h3',
                    ),
                    array(
                        'title' => esc_html__('Heading 4 view', 'colors-creative'),
                        'selector' => 'p,h1,h2,h3,h4,h5,h6,div',
                        'classes' => 'h4',
                    ),
                    array(
                        'title' => esc_html__('Heading 5 view', 'colors-creative'),
                        'selector' => 'p,h1,h2,h3,h4,h5,h6,div',
                        'classes' => 'h5',
                    ),
                    array(
                        'title' => esc_html__('Heading 6 view', 'colors-creative'),
                        'selector' => 'p,h1,h2,h3,h4,h5,h6,div',
                        'classes' => 'h6',
                    ),
                    array(
                        'title' => esc_html__('Well', 'colors-creative'),
                        'block' => 'div',
                        'classes' => 'well',
                    ),
                    array(
                        'title' => esc_html__('Large well', 'colors-creative'),
                        'block' => 'div',
                        'classes' => 'well well-lg',
                    ),
                    array(
                        'title' => esc_html__('Small well', 'colors-creative'),
                        'block' => 'div',
                        'classes' => 'well well-sm',
                    ),
                ),
            ),
            array(
                'title' => 'Colors',
                'items' => array(
                    array(
                        'title' => esc_html__('Muted', 'colors-creative'),
                        'selector' => 'a,span,p,h1,h2,h3,h4,h5,h6,div',
                        'classes' => 'text-muted',
                    ),
                    array(
                        'title' => esc_html__('Primary', 'colors-creative'),
                        'selector' => 'a,span,p,h1,h2,h3,h4,h5,h6,div',
                        'classes' => 'text-primary',
                    ),
                    array(
                        'title' => esc_html__('Success', 'colors-creative'),
                        'selector' => 'a,span,p,h1,h2,h3,h4,h5,h6,div',
                        'classes' => 'text-success',
                    ),
                    array(
                        'title' => esc_html__('Info', 'colors-creative'),
                        'selector' => 'a,span,p,h1,h2,h3,h4,h5,h6,div',
                        'classes' => 'text-info',
                    ),
                    array(
                        'title' => esc_html__('Warning', 'colors-creative'),
                        'selector' => 'a,span,p,h1,h2,h3,h4,h5,h6,div',
                        'classes' => 'text-warning',
                    ),
                    array(
                        'title' => esc_html__('Danger', 'colors-creative'),
                        'selector' => 'a,span,p,h1,h2,h3,h4,h5,h6,div',
                        'classes' => 'text-danger',
                    ),
                ),
            ),
        ));
    }

    return $mceInit;
}, 100, 2);