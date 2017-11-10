<?php

use Essentials\Data\Options,
    Essentials\Html\Css,
    Essentials\Templates\Layout;

add_action('wp_head', function () {
    $id = om_get_current_page_id();
    $background_color_behavior = Options::get('background_color_behavior_current', $id);

    $navigation_logo_height = Options::get('navigation_logo_height');

    if (!is_numeric($navigation_logo_height) || $navigation_logo_height < 10 || $navigation_logo_height > 84) {
        $navigation_logo_height = null;
    }

    $css = Css::init();
    $media_css = array();

    // Fonts
    // =====
    if (Options::specified('font_base')) {
        $font = Options::get('font_base');

        if ($font['font-type'] !== 'websafe') {
            $font['font-family'] .= ',sans-serif';
        }

        $css->set('body', array(
            'font-family' => $font['font-family'],
            'font-weight' => $font['font-weight']
        ));
    }

    if (Options::specified('font_headings')) {
        $font = Options::get('font_headings');

        if ($font['font-type'] !== 'websafe') {
            $font['font-family'] .= ',sans-serif';
        }

        $css->set(array('h1,.h1,h2,.h2,h3,.h3,h4,.h4,h5,.h5,h6,.h6', '.widget .widget-title'), array(
            'font-family' => $font['font-family'],
            'font-style' => $font['font-style'],
            'font-variant' => $font['font-variant'],
            'font-weight' => $font['font-weight'],
            'letter-spacing' => $font['letter-spacing'],
            'text-transform' => $font['text-transform'],
        ));
    }

    if (Options::specified('font_navigation')) {
        $font = Options::get('font_navigation');

        if ($font['font-type'] !== 'websafe') {
            $font['font-family'] .= ',sans-serif';
        }

        $css->set(array(
            '.navmenu-nav .nav>li>a',
            '.navmenu-nav .dropdown-menu>li>a'
        ), array(
            'font-family' => $font['font-family'],
            'font-size' => $font['font-size'],
            'font-style' => $font['font-style'],
            'font-variant' => $font['font-variant'],
            'font-weight' => $font['font-weight'],
            'letter-spacing' => $font['letter-spacing'],
            'text-transform' => $font['text-transform'],
        ));

        $css->set('.navmenu-nav .dropdown-menu>li>a', array(
            'font-size' => round((int) $font['font-size'] * 0.9) . 'px',
        ));
    }

    if (Options::specified('font_brand')) {
        $font = Options::get('font_brand');

        if ($font['font-type'] !== 'websafe') {
            $font['font-family'] .= ',sans-serif';
        }

        $css->set(array('.navmenu-brand'), array(
            'font-family' => $font['font-family'],
            'font-size' => $font['font-size'],
            'font-style' => $font['font-style'],
            'font-variant' => $font['font-variant'],
            'font-weight' => $font['font-weight'],
            'letter-spacing' => $font['letter-spacing'],
            'text-transform' => $font['text-transform'],
        ));
    }

    if (Options::specified('font_header_title')) {
        $font = Options::get('font_header_title');

        if ($font['font-type'] !== 'websafe') {
            $font['font-family'] .= ',sans-serif';
        }

        $font_size = (int) $font['font-size'];

        $css->set(array('.section-header .title'), array(
            'font-family' => $font['font-family'],
            'font-size' => round($font_size / 2.25) . 'px',
            'font-style' => $font['font-style'],
            'font-variant' => $font['font-variant'],
            'font-weight' => $font['font-weight'],
            'letter-spacing' => $font['letter-spacing'],
            'line-height' => $font['line-height'],
            'text-transform' => $font['text-transform'],
        ));

        $media_css[] = '@media(min-width:768px){.section-header .title{font-size:' . round($font_size / 1.64) . 'px}}';
        $media_css[] = '@media(min-width:992px){.section-header .title{font-size:' . round($font_size / 1.5) . 'px}}';
        $media_css[] = '@media(min-width:1320px){.section-header .title{font-size:' . $font['font-size'] . '}}';
    }

    if (Options::specified('font_header_subtitle')) {
        $font = Options::get('font_header_subtitle');

        if ($font['font-type'] !== 'websafe') {
            $font['font-family'] .= ',sans-serif';
        }

        $font_size = (int) $font['font-size'];

        $css->set(array('.section-header .subtitle'), array(
            'font-family' => $font['font-family'],
            'font-size' => round($font_size / 2.4) . 'px',
            'font-style' => $font['font-style'],
            'font-variant' => $font['font-variant'],
            'font-weight' => $font['font-weight'],
            'letter-spacing' => $font['letter-spacing'],
            'line-height' => $font['line-height'],
            'text-transform' => $font['text-transform'],
        ));

        $media_css[] = '@media(min-width:768px){.section-header .subtitle{font-size:' . round($font_size / 1.6) . 'px}}';
        $media_css[] = '@media(min-width:992px){.section-header .subtitle{font-size:' . round($font_size / 1.5) . 'px}}';
        $media_css[] = '@media(min-width:1320px){.section-header .subtitle{font-size:' . $font['font-size'] . '}}';
    }

    if (Options::specified('font_content_info')) {
        $font = Options::get('font_content_info');

        $css->set(array('.content-info'), array(
            'font-size' => $font['font-size'],
            'font-style' => $font['font-style'],
            'font-variant' => $font['font-variant'],
            'font-weight' => $font['font-weight'],
            'letter-spacing' => $font['letter-spacing'],
            'line-height' => $font['line-height'],
            'text-transform' => $font['text-transform'],
        ));
    }

    if (Options::specified('font_portfolio_categories')) {
        $font = Options::get('font_portfolio_categories');

        if ($font['font-type'] !== 'websafe') {
            $font['font-family'] .= ',sans-serif';
        }

        $css->set(array('.grid-filters .btn'), array(
            'font-family' => $font['font-family'],
            'font-size' => $font['font-size'],
            'font-style' => $font['font-style'],
            'font-variant' => $font['font-variant'],
            'font-weight' => $font['font-weight'],
            'letter-spacing' => $font['letter-spacing'],
            'line-height' => $font['line-height'],
            'text-transform' => $font['text-transform'],
        ));
    }


    // Common
    // ======
    $css->set('body', 'background', Options::get('body_background_color_current', $id));
    $css->set('body', 'color', Options::get('text_color_current', $id));
    $css->set('h1,.h1,h2,.h2,h3,.h3,h4,.h4,h5,.h5,h6,.h6', 'color', Options::get('headings_color'));
    $css->set(array(
        'a',
        '.btn-link'
    ), 'color', Options::get('link_color'));
    $css->set(array(
        'a:hover',
        'a:focus',
        '.btn-link:hover',
        '.btn-link:focus'
    ), 'color', Options::get('link_hover_color'));

    $css->set(array(
        '.background-light',
        '.section.background-light'
    ), 'color', Options::get('dark_color'));
    $css->set(array(
        '.background-light h1,.background-light .h1',
        '.background-light h2,.background-light .h2',
        '.background-light h3,.background-light .h3',
        '.background-light h4,.background-light .h4',
        '.background-light h5,.background-light .h5',
        '.background-light h6,.background-light .h6',
        '.section.background-light h1,.section.background-light .h1',
        '.section.background-light h2,.section.background-light .h2',
        '.section.background-light h3,.section.background-light .h3',
        '.section.background-light h4,.section.background-light .h4',
        '.section.background-light h5,.section.background-light .h5',
        '.section.background-light h6,.section.background-light .h6'
    ), 'color', Options::get('dark_headings_color'));
    $css->set(array(
        '.background-light a:not(.btn)',
        '.background-light .btn-link',
        '.section.background-light a:not(.btn)',
        '.section.background-light .btn-link',
    ), 'color', Options::get('dark_link_color'));
    $css->set(array(
        '.background-light a:not(.btn):hover',
        '.background-light a:not(.btn):focus',
        '.background-light .btn-link:hover',
        '.background-light .btn-link:focus',
        '.section.background-light a:not(.btn):hover',
        '.section.background-light a:not(.btn):focus',
        '.section.background-light .btn-link:hover',
        '.section.background-light .btn-link:focus'
    ), 'color', Options::get('dark_link_hover_color'));

    $css->set(array(
        '.background-dark',
        '.section.background-dark'
    ), 'color', Options::get('light_color'));
    $css->set(array(
        '.background-dark h1,.background-dark .h1',
        '.background-dark h2,.background-dark .h2',
        '.background-dark h3,.background-dark .h3',
        '.background-dark h4,.background-dark .h4',
        '.background-dark h5,.background-dark .h5',
        '.background-dark h6,.background-dark .h6',
        '.section.background-dark h1,.section.background-dark .h1',
        '.section.background-dark h2,.section.background-dark .h2',
        '.section.background-dark h3,.section.background-dark .h3',
        '.section.background-dark h4,.section.background-dark .h4',
        '.section.background-dark h5,.section.background-dark .h5',
        '.section.background-dark h6,.section.background-dark .h6'
    ), 'color', Options::get('light_headings_color'));
    $css->set(array(
        '.background-dark a:not(.btn)',
        '.background-dark .btn-link',
        '.section.background-dark a:not(.btn)',
        '.section.background-dark .btn-link'
    ), 'color', Options::get('light_link_color'));
    $css->set(array(
        '.background-dark a:not(.btn):hover',
        '.background-dark a:not(.btn):focus',
        '.background-dark .btn-link:hover',
        '.background-dark .btn-link:focus',
        '.section.background-dark a:not(.btn):hover',
        '.section.background-dark a:not(.btn):focus',
        '.section.background-dark .btn-link:hover',
        '.section.background-dark .btn-link:focus'
    ), 'color', Options::get('light_link_hover_color'));

    $css->set(array('::-moz-selection', '::selection'), array(
        'background' => Options::get('selection_background_color'),
        'color' => Options::get('selection_color')
    ));

    // Forms
    // =====

    $css->set('.form-control', array(
        'background-color' => Options::get('form_control_bg_color'),
        'border-color' => Options::get('form_control_border_color'),
        'color' => Options::get('form_control_color')));

    $css->set('.form-control:focus', array(
        'background-color' => Options::get('form_control_focus_bg_color'),
        'border-color' => Options::get('form_control_focus_border_color'),
        'color' => Options::get('form_control_focus_color')));

    $css->set('select.form-control', array(
        'background-color' => Options::get('form_control_select_bg_color'),
        'border-color' => Options::get('form_control_select_border_color'),
        'color' => Options::get('form_control_select_color')));

    $css->set('select.form-control:focus', array(
        'background-color' => Options::get('form_control_select_focus_bg_color'),
        'border-color' => Options::get('form_control_select_focus_border_color'),
        'color' => Options::get('form_control_select_focus_color')));

    if (Options::specified('form_control_color') || Options::specified('form_control_select_color')) {
        $svg_color = Options::specified('form_control_select_color') ? Options::get('form_control_select_color') : Options::get('form_control_color');

        $css->set('select.form-control,select.form-control:focus', 'background-image', 'url("' . om_get_select_svg_data_url($svg_color) . '")');
    }

    if (Options::specified('form_control_placeholder')) {
        $css->set('.form-control::-moz-placeholder', 'color', Options::get('form_control_placeholder'));
        $css->set('.form-control:-ms-input-placeholder', 'color', Options::get('form_control_placeholder'));
        $css->set('.form-control::-webkit-input-placeholder', 'color', Options::get('form_control_placeholder'));
    }

    // Splash
    // ======

    $css->set('.splash', 'background-color', om_get_splash_background());
    $css->set('.splash .splash-icon g', 'stroke', Options::get('dark_splash_icon_color'));
    $css->set('.splash.splash-dark .splash-icon g', 'stroke', Options::get('light_splash_icon_color'));

    // Navigation
    // ==========

    $css->set('.navmenu-header-overlay', array(
        'background-color' => Options::get('navigation_header_overlay_background_color'),
        'opacity' => Options::get('navigation_header_overlay_background_transparency')));
    if (Options::specified('navigation_nav_background_image')) {
        $css->set('.navmenu .nav-background-image', 'background-image', "url('" . Options::get_image('navigation_nav_background_image') . "')");
    }
    $css->set('.navmenu-nav .nav-background-overlay', array(
        'background-color' => Options::get('navigation_nav_background_color'),
        'opacity' => Options::get('navigation_nav_background_transparency')));

    // Brand & Toggle

    if (!empty($navigation_logo_height)) {
        $value = $navigation_logo_height;

        if ($value > 50) {
            $value = max(50, floor($value / 1.2));
        }

        $css->set('.navmenu-brand', array(
            'height' => $value . 'px',
            'margin-top' => floor(-$value / 2) . 'px',
            'line-height' => $value . 'px'
        ));
    }

    $css->set(array(
        '.navmenu-header .navmenu-brand',
        '.background-light .navmenu-header .navmenu-brand',
        '.background-dark .navmenu-header .navmenu-brand'
    ), 'color', Options::get('navigation_nav_item_color'));
    $css->set(array(
        '.navmenu-header .navmenu-brand:hover',
        '.navmenu-header .navmenu-brand:focus',
        '.background-light .navmenu-header .navmenu-brand:hover',
        '.background-light .navmenu-header .navmenu-brand:focus',
        '.background-dark .navmenu-header .navmenu-brand:hover',
        '.background-dark .navmenu-header .navmenu-brand:focus'
    ), 'color', Options::get('navigation_nav_item_hover_color'));
    $css->set(array(
        '.navmenu-header.background-light .navmenu-brand',
        '.navmenu-header.background-light .navmenu-brand>.dark'
    ), 'color', Options::get('navigation_nav_dark_item_color'));
    $css->set(array(
        '.navmenu-header.background-light .navmenu-brand:hover',
        '.navmenu-header.background-light .navmenu-brand:focus',
        '.navmenu-header.background-light .navmenu-brand:hover>.dark',
        '.navmenu-header.background-light .navmenu-brand:focus>.dark'
    ), 'color', Options::get('navigation_nav_dark_item_hover_color'));
    $css->set(array(
        '.navmenu-header.background-dark .navmenu-brand',
        '.navmenu-header.background-dark .navmenu-brand>.light'
    ), 'color', Options::get('navigation_nav_light_item_color'));
    $css->set(array(
        '.navmenu-header.background-dark .navmenu-brand:hover',
        '.navmenu-header.background-dark .navmenu-brand:focus',
        '.navmenu-header.background-dark .navmenu-brand:hover>.light',
        '.navmenu-header.background-dark .navmenu-brand:focus>.light'
    ), 'color', Options::get('navigation_nav_light_item_hover_color'));

    $css->set('.navmenu-toggle .icon-bar', 'background-color', Options::get('navigation_nav_item_color'));
    $css->set('.navmenu-header.background-light .navmenu-toggle .icon-bar', 'background-color', Options::get('navigation_nav_dark_item_color'));
    $css->set('.navmenu-header.background-dark .navmenu-toggle .icon-bar', 'background-color', Options::get('navigation_nav_light_item_color'));

    // Menu

    $css->set('.navmenu-nav .nav>li>a', 'color', Options::get('navigation_nav_item_color'));
    $css->set(array(
        '.navmenu-nav .nav>li>a:hover',
        '.navmenu-nav .nav>li>a:focus',
        '.navmenu-nav .nav>.active>a',
        '.navmenu-nav .nav>.open>a',
    ), 'color', Options::get('navigation_nav_item_hover_color'));
    $css->set('.navmenu-nav .dropdown-menu', 'background-color', Options::get('navigation_nav_secondary_background_color'));
    $css->set('.navmenu-nav .dropdown-menu>li>a', 'color', Options::get('navigation_nav_secondary_item_color'));
    $css->set(array(
        '.navmenu-nav .dropdown-menu>li>a:hover',
        '.navmenu-nav .dropdown-menu>li>a:focus',
        '.navmenu-nav .dropdown-menu>.active>a',
        '.navmenu-nav .dropdown-menu>.open>a',
    ), 'color', Options::get('navigation_nav_secondary_item_hover_color'));

    // Dark & Light Menu

    $css->set(array(
        '.navmenu-nav.background-light .nav>li>a',
        '.navmenu-nav .dropdown-menu.background-light>li>a'
    ), 'color', Options::get('navigation_nav_dark_item_color'));
    $css->set(array(
        '.navmenu-nav.background-light .nav>li>a:hover',
        '.navmenu-nav.background-light .nav>li>a:focus',
        '.navmenu-nav.background-light .nav>.active>a',
        '.navmenu-nav.background-light .nav>.open>a',
        '.navmenu-nav .dropdown-menu.background-light>li>a:hover',
        '.navmenu-nav .dropdown-menu.background-light>li>a:focus',
        '.navmenu-nav .dropdown-menu.background-light>.active>a',
    ), 'color', Options::get('navigation_nav_dark_item_hover_color'));

    $css->set(array(
        '.navmenu-nav.background-dark .nav>li>a',
        '.navmenu-nav .dropdown-menu.background-dark>li>a'
    ), 'color', Options::get('navigation_nav_light_item_color'));
    $css->set(array(
        '.navmenu-nav.background-dark .nav>li>a:hover',
        '.navmenu-nav.background-dark .nav>li>a:focus',
        '.navmenu-nav.background-dark .nav>.active>a',
        '.navmenu-nav.background-dark .nav>.open>a',
        '.navmenu-nav .dropdown-menu.background-dark>li>a:hover',
        '.navmenu-nav .dropdown-menu.background-dark>li>a:focus',
        '.navmenu-nav .dropdown-menu.background-dark>.active>a',
    ), 'color', Options::get('navigation_nav_light_item_hover_color'));


    // Footer
    // ======

    $css->set('.content-info .content-info-section.content-info-primary', array(
        'background-color' => $background_color_behavior == 'static' ? Options::get('footer_primary_background_color_current', $id) : '',
        'color' => Options::get('footer_primary_text_color_current', $id),
    ));
    $css->set('.content-info .content-info-section.content-info-primary a', 'color', Options::get('footer_primary_link_color_current',$id));
    $css->set(array(
        '.content-info .content-info-section.content-info-primary a:hover',
        '.content-info .content-info-section.content-info-primary a:focus'
    ), 'color', Options::get('footer_primary_link_color_hover_current',$id));

    $css->set('.content-info .content-info-section.content-info-secondary', array(
        'background-color' => $background_color_behavior == 'static' ? Options::get('footer_secondary_background_color_current',$id) : '',
        'color' => Options::get('footer_secondary_text_color_current',$id),
    ));
    $css->set('.content-info .content-info-section.content-info-secondary a', 'color', Options::get('footer_secondary_link_color_current',$id));
    $css->set(array(
        '.content-info .content-info-section.content-info-secondary a:hover',
        '.content-info .content-info-section.content-info-secondary a:focus'
    ), 'color', Options::get('footer_secondary_link_color_hover_current',$id));

    if (Options::get('sidebar_hide_widgets_title_icons')) {
        $css->set('.content-info .widget .widgettitle:before', 'display', 'none');
    }

    // Dark & Light

    $css->set('.background-light .content-info-section', 'color', Options::get('dark_color'));
    $css->set('.background-light .content-info-section a', 'color', Options::get('dark_link_color'));
    $css->set(array(
        '.background-light .content-info-section a:hover',
        '.background-light .content-info-section a:focus'
    ), 'color', Options::get('dark_link_hover_color'));

    $css->set('.background-dark .content-info-section', 'color', Options::get('light_color'));
    $css->set('.background-dark .content-info-section a', 'color', Options::get('light_link_color'));
    $css->set(array(
        '.background-dark .content-info-section a:hover',
        '.background-dark .content-info-section a:focus'
    ), 'color', Options::get('light_link_hover_color'));

    // Buttons
    // =======

    $btn_primary_color = Options::get('btn_primary_color');
    $btn_primary_bg = Options::get('btn_primary_background');
    $btn_primary_border = Options::get('btn_primary_border');

    $btn_primary_bg_hover = om_color_darken($btn_primary_bg, 10);
    $btn_primary_border_hover = om_color_darken($btn_primary_border, 12);

    om_btn_color($css, 'primary', $btn_primary_color, $btn_primary_bg, $btn_primary_border, $btn_primary_bg_hover, $btn_primary_border_hover);

    $btn_default_color = Options::get('btn_default_color');
    $btn_default_bg = Options::get('btn_default_background');
    $btn_default_border = Options::get('btn_default_border');

    $btn_default_bg_hover = om_color_darken($btn_default_bg, 10);
    $btn_default_border_hover = om_color_darken($btn_default_border, 12);

    om_btn_color($css, 'default', $btn_default_color, $btn_default_bg, $btn_default_border, $btn_default_bg_hover, $btn_default_border_hover);

    // Woocommerce buttons

     $css->set(array(
         '.woocommerce #respond input#submit'
     ), array(
         'background-color' => $btn_primary_bg,
         'color' => $btn_primary_color
     ));
     $css->set(array(
         '.woocommerce #respond input#submit:hover',
         '.woocommerce #respond input#submit:focus'
     ), 'background-color', $btn_primary_bg_hover);

    // Posts grid button

    $css->set(array(
        '.grid.hentry-grid .hentry .caption:hover .btn-read',
        '.grid.hentry-grid .hentry .caption:focus .btn-read'
    ), array(
        'background-color' => $btn_primary_bg,
        'color' => $btn_primary_color
    ));

    $css->set('.grid.hentry-grid .hentry.sticky .caption:after', 'background-color', $btn_primary_bg);

    $css->set(array(
        '.grid.hentry-grid .hentry .caption:hover .btn-read:hover',
        '.grid.hentry-grid .hentry .caption:focus .btn-read:hover',
        '.grid.hentry-grid .hentry .caption:hover .btn-read:focus',
        '.grid.hentry-grid .hentry .caption:focus .btn-read:focus'
    ), 'background-color', $btn_primary_bg_hover);

    // Page header
    // ===========

    if (Options::specified('header_image', $id)) {
        $header_image = Options::get_image('header_image', 'full', $id);

    } elseif (Options::get('header_use_featured_image_current', $id)) {
        $header_image_id = get_post_thumbnail_id();
        if (!empty($header_image_id)) {
            $header_image_info = wp_get_attachment_image_src($header_image_id, 'full');

            if ($header_image_info) {
                $header_image = $header_image_info[0];
            }
        }
    }

    $css->set('.section-header .section-background-main', 'background-color', Options::get('header_background_color', $id));
    if (isset($header_image)) {
        $css->set('.section-header .section-background-image', array(
            'background-size' => Options::get('header_image_size', $id),
            'background-image' => "url('" . esc_url($header_image) . "')"
        ));
    }
    $css->set('.section-header .section-background-overlay', 'background-color', Options::get('header_overlay_color', $id));
    $css->set('.section.section-header .title', 'color', Options::get('header_title_color', $id));
    $css->set('.section.section-header .subtitle', 'color', Options::get('header_subtitle_color', $id));
    $css->set('.section.section-header .description', 'color', Options::get('header_text_color', $id));

    // Posts meta
    // ==========

    $css->set('.grid.hentry-grid .hentry .caption .background-overlay', 'background-color', Options::get('index_item_overlay_color'));
    $css->set('.grid.hentry-grid .hentry .caption .background-rest', 'background-color', Options::get('index_item_background_color'));
    $css->set('.grid.hentry-grid .hentry .caption .background-hover', 'background-color', Options::get('index_item_hover_background_color'));

    $css->set('.hentry-list .hentry .entry-thumbnail-overlay', 'background-color', Options::get('index_item_overlay_color'));
    $css->set('.hentry-list .hentry .entry-thumbnail:hover .entry-thumbnail-overlay', 'background-color', Options::get('index_item_hover_background_color'));
    if(Options::specified('index_item_background_color')) {
        $css->set('.hentry-list .hentry .entry-content', array(
            'background-color' => Options::get('index_item_background_color'),
            'padding' => '0.7em 1em 1.2em 1.6em'
        ));
    }
    $css->set('.hentry-list .hentry .entry-title a', 'color', Options::get('index_item_title_color'));
    $css->set(array(
        '.hentry-list .hentry .entry-title a:hover',
        '.hentry-list .hentry .entry-title a:focus'
    ), 'color', Options::get("index_item_hover_title_color"));
    $css->set(array(
        '.hentry-list .hentry .entry-summary',
        '.hentry-list .hentry .entry-meta'
    ), 'color', Options::get('index_item_meta_color'));

    $css->set('.hentry-classic .hentry .entry-thumbnail-overlay', 'background-color', Options::get('index_item_overlay_color'));
    $css->set('.hentry-classic .hentry .entry-thumbnail:hover .entry-thumbnail-overlay', 'background-color', Options::get('index_item_hover_background_color'));

    if(Options::specified('index_item_background_color')) {
        $css->set('.hentry-classic .hentry .entry-content', array(
            'background-color' => Options::get('index_item_background_color')/*,
            'padding' => '0.7em 1em 1.2em 1.5em'*/
        ));
    }
    $css->set('.hentry-classic .hentry .entry-title a', 'color', Options::get('index_item_title_color'));
    $css->set(array(
        '.hentry-classic .hentry .entry-title a:hover',
        '.hentry-classic .hentry .entry-title a:focus'
    ), 'color', Options::get("index_item_hover_title_color"));
    $css->set(array(
        '.hentry-classic .hentry .entry-summary',
        '.hentry-classic .hentry .entry-meta'
    ), 'color', Options::get('index_item_meta_color'));
   // $css->set('.hentry-classic .hentry', 'background-color', Options::get('index_item_background_color'));

    //$css->set('.hentry-classic .hentry .entry-meta', 'background-color', Options::get('index_item_background_color'));

    // Mobile
    $index_item_mobile_colors = Options::specified('index_item_mobile_colors') ? Options::get('index_item_mobile_colors') : 'hover';
    $index_mobile_suffix = $index_item_mobile_colors == 'hover' ? 'hover_' : '';

    $css->set('.grid.hentry-grid .hentry .caption .background-mobile', 'background-color', Options::get("index_item_{$index_mobile_suffix}background_color"));
    $css->set('.grid.hentry-grid .hentry .caption .entry-title a', 'color', Options::get("index_item_{$index_mobile_suffix}title_color"));
    $css->set(array(
        '.grid.hentry-grid .hentry .caption .entry-meta',
        '.grid.hentry-grid .hentry .caption .entry-meta a'
    ), 'color', Options::get("index_item_{$index_mobile_suffix}meta_color"));



    // Lightbox
    // ========

    $css->set(array(
        '.pswp__bg',
        '.pswp--zoomed-in .pswp__top-bar,.pswp--zoomed-in .pswp__caption'
    ), 'background-color', Options::get('lightbox_background'));
    $css->set('.pswp__button', 'color', Options::get('lightbox_button_color'));
    $css->set('.pswp__button--arrow--left,.pswp__button--arrow--right', 'color', Options::get('lightbox_arrow_color'));
    $css->set('.pswp__button--arrow--left,.pswp__button--arrow--right', 'background-color', Options::get('lightbox_arrow_background'));
    $css->set('.pswp__counter', 'color', Options::get('lightbox_counter_color'));
    $css->set('.pswp__caption', 'color', Options::get('lightbox_caption_color'));
    $css->set('.pswp__share-modal .pswp__share-tooltip a', 'color', Options::get('lightbox_share_color'));
    $css->set('.pswp__share-modal .pswp__share-tooltip a', 'background-color', Options::get('lightbox_share_background'));
    $css->set('.pswp__share-modal', 'background-color', Options::get('lightbox_overlay_color'));
    $css->set('.pswp__img--placeholder--blank', 'background-color', Options::get('lightbox_image_background_color'));

    // WooCommerce
    // ===========

    if (om_is_wc()) {

        // Navigation Cart

        $css->set('.navmenu-cart', 'color', Options::get('navigation_nav_item_color'));
        $css->set(array(
            '.navmenu-cart:hover',
            '.navmenu-cart:focus'
        ), 'color', Options::get('navigation_nav_item_hover_color'));
        $css->set('.navmenu-header.background-light .navmenu-cart', 'color', Options::get('navigation_nav_dark_item_color'));
        $css->set(array(
            '.navmenu-header.background-light .navmenu-cart:hover',
            '.navmenu-header.background-light .navmenu-cart:focus'
        ), 'color', Options::get('navigation_nav_dark_item_hover_color'));
        $css->set('.navmenu-header.background-dark .navmenu-cart', 'color', Options::get('navigation_nav_light_item_color'));
        $css->set(array(
            '.navmenu-header.background-dark .navmenu-cart:hover',
            '.navmenu-header.background-dark .navmenu-cart:focus'
        ), 'color', Options::get('navigation_nav_light_item_hover_color'));

        // Loop

        $loop_product_text_align = Options::get('wc_loop_product_text_align');

        $css->set('.woocommerce .product-list .product', array(
            'background-color' => Options::get('wc_loop_product_bg_color'),
            'text-align' => in_array($loop_product_text_align, array('center', 'right'), true) ? $loop_product_text_align : null
        ));

            //New
        $css->set('.woocommerce .product .onsale', 'background-color', Options::get('wc_loop_onsale_bg_color'));
        $css->set('.woocommerce .product .onsale', 'color', Options::get('wc_loop_onsale_color'));
        $css->set('.woocommerce div.product p.price, .woocommerce div.product span.price', 'color', Options::get('wc_loop_product_item_price_color'));
        $css->set('.woocommerce div.product p.price>del', 'color', Options::get('wc_loop_product_item_old_price_color'));
        $css->set('.woocommerce div.product p.price>ins', 'color', Options::get('wc_loop_product_item_new_price_color'));
        $css->set('.woocommerce .star-rating>span', 'color', Options::get('wc_loop_star_rating_color'));
        $css->set('.navmenu-cart .count', 'background', Options::get('wc_loop_menu_cart_bg_color'));
        $css->set('.navmenu-cart .count', 'color', Options::get('wc_loop_menu_cart_color'));

        
        $css->set('.woocommerce .product-list .product .title h3', 'color', Options::get('wc_loop_product_title_color'));
        $css->set(array(
            '.woocommerce .product-list .product .title:hover h3',
            '.woocommerce .product-list .product .title:focus h3'
        ), 'color', Options::get('wc_loop_product_title_color_hover'));
        $css->set('.woocommerce .product-list .product .price', 'color', Options::get('wc_loop_product_price_color'));
        $css->set('.woocommerce .product-list .product .price>ins', 'color', Options::get('wc_loop_product_new_price_color'));
       // $css->set('.woocommerce div.product p.price>del', 'color', Options::get('wc_loop_product_old_price_color'));
        $css->set('.woocommerce .product-list .product .price>del', 'color', Options::get('wc_loop_product_old_price_color'));
        $css->set(array(
            '.woocommerce .product .product-label',
            '.woocommerce .product .product-label:hover',
            '.woocommerce .product .product-label:focus'
        ), array(
            'color' => Options::get('wc_loop_product_label_color'),
            'background-color' => Options::get('wc_loop_product_label_bg_color')));

        $css->set('.woocommerce .product-list .product-category .badge', array(
            'color' => Options::get('wc_loop_product_badge_color'),
            'background-color' => Options::get('wc_loop_cat_badge_bg_color')));

        $css->set('.btn.btn-add-to-cart', array(
            'color' => Options::get('wc_loop_btn_color'),
            'background-color' => Options::get('wc_loop_btn_bg_color'),
            'border-color' => Options::get('wc_loop_btn_bg_color')
        ));

        $css->set(array(
            '.btn.btn-add-to-cart:hover',
            '.btn.btn-add-to-cart:focus',
            '.btn.btn-add-to-cart:active'
        ), array(
            'color' => Options::get('wc_loop_btn_color_hover'),
            'background-color' => Options::get('wc_loop_btn_bg_color_hover'),
            'border-color' => Options::get('wc_loop_btn_bg_color_hover')
        ));

        if (in_array($loop_product_text_align, array('center', 'right'), true)) {
            $css->set('.woocommerce .product .star-rating-loop .star-rating', 'margin-left', 'auto');

            if ($loop_product_text_align == 'center') {
                $css->set('.woocommerce .product .star-rating-loop .star-rating', 'margin-right', 'auto');
            }
        }

        if (Options::specified('wc_loop_product_bg_color') && om_is_dark(Options::get('wc_loop_product_bg_color'))) {
            $css->set('.woocommerce .star-rating-loop .star-rating:before', 'color', '#777');
        }

        $h_padding = Options::get('wc_loop_product_h_padding');

        if ($h_padding === '0' || !empty($h_padding)) {

            $h_padding .= 'px';

            $css->set(array(
                '.woocommerce .product-list .product .title',
                '.woocommerce .product-list .product .star-rating-loop',
                '.woocommerce .product-list .product .price'
            ), array(
                'padding-left' => $h_padding,
                'padding-right' => $h_padding
            ));
        }
    }

    $styles = (string)$css;

    // ********
    // >= 992px
    // ********

    $css->clear();

    // Posts index
    // ===========

    $css->set('.grid.hentry-grid .hentry .caption .entry-title a', 'color', Options::get('index_item_title_color'));
    $css->set(array(
        '.grid.hentry-grid .hentry .caption .entry-meta',
        '.grid.hentry-grid .hentry .caption .entry-meta a',
        '.grid.hentry-grid .hentry .caption .btn-read'
    ), 'color', Options::get('index_item_meta_color'));

    $css->set('.grid.hentry-grid .hentry .caption:hover .entry-title a', 'color', Options::get('index_item_hover_title_color'));
    $css->set(array(
        '.grid.hentry-grid .hentry .caption:hover .entry-meta',
        '.grid.hentry-grid .hentry .caption:hover .entry-meta a'
    ), 'color', Options::get('index_item_hover_meta_color'));

    $css->set('.grid.hentry-grid .hentry.sticky .caption:hover:after', 'color', $btn_primary_color);

    $styles .= '@media(min-width:992px){' . (string)$css . '}';

    // ********
    // >= 1320px
    // ********

    $css->clear();

    // Navigation
    // ==========

    // Brand & Toggle

    if (!empty($navigation_logo_height)) {
        $css->set('.navmenu-brand', array(
            'height' => $navigation_logo_height . 'px',
            'margin-top' => floor(-$navigation_logo_height / 2) . 'px',
            'line-height' => $navigation_logo_height . 'px'
        ));
    }

    $styles .= '@media(min-width:1320px){' . (string)$css . '}';

    if ($styles) {
        $styles .= implode('', $media_css);

        $styles = apply_filters('om_theme_page_styles', $styles);

        if ($styles) {
            echo '<style type="text/css">', om_esc_styles($styles), '</style>';
        }
    }

    if (Options::specified('custom_css')) {
        $custom_css = apply_filters('om_theme_custom_styles', Options::get('custom_css'));

        if (!empty($custom_css)) {
            echo '<style type="text/css">', om_esc_styles($custom_css), '</style>';
        }
    }

    $template_name = method_exists('\Essentials\Templates\Layout', 'get_template_name') ? Layout::get_template_name() : null;

    // Index template
    // ==============

    if ($template_name === 'index') {
        /**
         * @var WP_Query $wp_query
         */
        global $wp_query;

        $post_ids = from($wp_query->posts)->select('$v->ID')->toArray();
        $styles = '';

        $css->clear();

        foreach ($post_ids as $post_id) {
            $css->set(".grid.hentry-grid .hentry.post-{$post_id} .caption .background-overlay", 'background-color', Options::get('index_item_current_overlay_color', $post_id));
            $css->set(".grid.hentry-grid .hentry.post-{$post_id} .caption .background-rest", 'background-color', Options::get('index_item_current_background_color', $post_id));
            $css->set(".grid.hentry-grid .hentry.post-{$post_id} .caption .background-hover", 'background-color', Options::get('index_item_current_hover_background_color', $post_id));

            // Mobile
            $css->set(".grid.hentry-grid .hentry.post-{$post_id} .caption .background-mobile", 'background-color', Options::get("index_item_current_{$index_mobile_suffix}background_color", $post_id));
            $css->set(".grid.hentry-grid .hentry.post-{$post_id} .caption .entry-title a", 'color', Options::get("index_item_current_{$index_mobile_suffix}title_color", $post_id));
            $css->set(array(
                ".grid.hentry-grid .hentry.post-{$post_id} .caption .entry-meta",
                ".grid.hentry-grid .hentry.post-{$post_id} .caption .entry-meta a"
            ), 'color', Options::get("index_item_current_{$index_mobile_suffix}meta_color", $post_id));
        }

        $styles .= (string)$css;

        $css->clear();

        foreach ($post_ids as $post_id) {
            $css->set(".grid.hentry-grid .hentry.post-{$post_id} .caption .entry-title a", 'color', Options::get('index_item_current_title_color', $post_id));
            $css->set(array(
                ".grid.hentry-grid .hentry.post-{$post_id} .caption .entry-meta",
                ".grid.hentry-grid .hentry.post-{$post_id} .caption .entry-meta a",
                ".grid.hentry-grid .hentry.post-{$post_id} .caption .btn-read"
            ), 'color', Options::get('index_item_current_meta_color', $post_id));

            $css->set(".grid.hentry-grid .hentry.post-{$post_id} .caption:hover .entry-title a", 'color', Options::get('index_item_current_hover_title_color', $post_id));
            $css->set(array(
                ".grid.hentry-grid .hentry.post-{$post_id} .caption:hover .entry-meta",
                ".grid.hentry-grid .hentry.post-{$post_id} .caption:hover .entry-meta a"
            ), 'color', Options::get('index_item_current_hover_meta_color', $post_id));
        }

        $styles .= '@media(min-width:992px){' . (string)$css . '}';

        $styles = apply_filters('om_theme_index_template_styles', $styles);

        if (!empty($styles)) {
            echo '<style type="text/css">', om_esc_styles($styles), '</style>';
        }
    }
}, 100);

/**
 * @param $css Essentials\Html\Css
 * @param $type string
 * @param $color string
 * @param $bg string
 * @param $border string
 * @param $hover_bg string
 * @param $hover_border string
 */
function om_btn_color(&$css, $type, $color, $bg, $border, $hover_bg, $hover_border)
{
    $css->set('.btn-' . $type, array(
        'color' => $color,
        'background-color' => $bg,
        'border-color' => $border
    ));

    $css->set(array(
        '.btn-' . $type . ':hover',
        '.btn-' . $type . ':focus',
        '.btn-' . $type . '.focus',
        '.btn-' . $type . ':active',
        '.btn-' . $type . '.active',
        '.open>.dropdown-toggle.btn-primary'
    ), array(
        'color' => $color,
        'background-color' => $hover_bg,
        'border-color' => $hover_border
    ));

    $css->set(array(
        '.btn-' . $type . '.disabled',
        '.btn-' . $type . '[disabled]',
        'fieldset[disabled].btn-' . $type,
        '.btn-' . $type . '.disabled:hover',
        '.btn-' . $type . '[disabled]:hover',
        'fieldset[disabled].btn-' . $type . ':hover',
        '.btn-' . $type . '.disabled:focus',
        '.btn-' . $type . '[disabled]:focus',
        'fieldset[disabled].btn-' . $type . ':focus',
        '.btn-' . $type . '.disabled:active',
        '.btn-' . $type . '[disabled]:active',
        'fieldset[disabled].btn-' . $type . ':active',
        '.btn-' . $type . '.disabled.active',
        '.btn-' . $type . '[disabled].active',
        'fieldset[disabled].btn-' . $type . '.active'
    ), array(
        'background-color' => $bg,
        'border-color' => $border
    ));
    $css->set('.btn-' . $type . ' . badge', array(
        'color' => $bg,
        'background-color' => $color
    ));

    // Ghost

    $css->set('.btn-ghost.btn-' . $type, array(
        'border-color' => $bg,
        'color' => $bg
    ));

    $css->set(array(
        '.btn-ghost.btn-' . $type . ':hover',
        '.btn-ghost.btn-' . $type . ':focus'
    ), array(
        'background' => $bg,
        'color' => $color
    ));

    // Flat override

    $css->set(array(
        '.btn-' . $type . '.btn-flat',
        '.btn-' . $type . '.btn-flat:hover',
        '.btn-' . $type . '.btn-flat:focus'), 'border-color', 'transparent');

    // 3D override

    $css->set('.btn-' . $type . '.btn-3d', array(
        'border-color' => 'transparent',
        'box-shadow' => '0 4px 0 ' . $border
    ));
    $css->set(array(
        '.btn-' . $type . '.btn-3d:hover',
        '.btn-' . $type . '.btn-3d:focus',
        '.btn-' . $type . '.btn-3d:active',
        '.btn-' . $type . '.btn-3d.active'
    ), 'background-color', $bg);
    $css->set(array(
        '.btn-' . $type . '.btn-3d:hover',
        '.btn-' . $type . '.btn-3d:focus'
    ), 'box-shadow', '0 2px 0  ' . $border);
    $css->set(array(
        '.btn-' . $type . '.btn-3d:active',
        '.btn-' . $type . '.btn-3d.active'
    ), 'box-shadow', '0 1px 0 ' . $border);
    $css->set('.btn-' . $type . '.btn-3d.btn-xs', 'box-shadow', '0 2px 0 ' . $border);
    $css->set(array(
        '.btn-' . $type . '.btn-3d.btn-xs:hover',
        '.btn-' . $type . '.btn-3d.btn-xs:focus'
    ), 'box-shadow', '0 1px 0 ' . $border);
    $css->set(array(
        '.btn-' . $type . '.btn-3d.btn-xs:active',
        '.btn-' . $type . '.btn-3d.btn-xs.active'
    ), 'box-shadow', '0 1px 0 ' . $border);
    $css->set('.btn-' . $type . '.btn-3d.btn-sm', 'box-shadow', '0 3px 0 ' . $border);
    $css->set(array(
        '.btn-' . $type . '.btn-3d.btn-sm:hover',
        '.btn-' . $type . '.btn-3d.btn-sm:focus'), 'box-shadow', '0 2px 0 ' . $border);
    $css->set(array(
        '.btn-' . $type . '.btn-3d.btn-sm:active',
        '.btn-' . $type . '.btn-3d.btn-sm.active'
    ), 'box-shadow', '0 1px 0 ' . $border);
    $css->set('.btn-' . $type . '.btn-3d.btn-lg', 'box-shadow', '0 6px 0 ' . $border);
    $css->set(array(
        '.btn-' . $type . '.btn-3d.btn-lg:hover',
        '.btn-' . $type . '.btn-3d.btn-lg:focus'
    ), 'box-shadow', '0 3px 0 ' . $border);
    $css->set(array(
        '.btn-' . $type . '.btn-3d.btn-lg:active',
        '.btn-' . $type . '.btn-3d.btn-lg.active'
    ), 'box-shadow', '0 2px 0 ' . $border);
    $css->set('.btn-' . $type . '.btn-3d.btn-xl', 'box-shadow', '0 8px 0 ' . $border);
    $css->set(array(
        '.btn-' . $type . '.btn-3d.btn-xl:hover',
        '.btn-' . $type . '.btn-3d.btn-xl:focus'
    ), 'box-shadow', '0 4px 0 ' . $border);
    $css->set(array(
        '.btn-' . $type . '.btn-3d.btn-xl:active',
        '.btn-' . $type . '.btn-3d.btn-xl.active'
    ), 'box-shadow', '0 2px 0 ' . $border);
    $css->set('.btn-' . $type . '.btn-link', array(
        'background' => 'none',
        'border-color' => 'transparent',
        'color' => $bg,
    ));

    // Link override

    $css->set(array(
        '.btn-' . $type . '.btn-link:hover',
        '.btn-' . $type . '.btn-link:focus',
        '.btn-' . $type . '.btn-link:active',
        '.btn-' . $type . '.btn-link.active'
    ), array('color' => $border));
}

/**
 * @param string $color
 *
 * @return string
 */
function om_get_select_svg_data_url($color)
{
    $svg = '<svg xmlns="http://www.w3.org/2000/svg" height="6" width="9"><path fill="' . $color . '" stroke="' . $color . '" stroke-width="1" stroke-linecap="square" d="M0.5,0.5 l0,1 l4,4 l4,-4 l0,-1 l-1,0 l-3,3 l-3,-3 l-1,0"/></svg>';

    return Css::getSvgUrl($svg);
}