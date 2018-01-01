<?php

add_filter('ome_menu_html', function ($html) {
    /**
     * @var \Essentials\Html\Writer $html
     * @var SitePress $sitepress
     * @var SitePressLanguageSwitcher $icl_language_switcher
     */
    global $sitepress, $icl_language_switcher;

    if (null !== $sitepress && null !== $icl_language_switcher && isset($icl_language_switcher->settings['display_ls_in_menu']) && $icl_language_switcher->settings['display_ls_in_menu']) {
        $switcher_type = $icl_language_switcher->settings['icl_lang_sel_type']; // 'dropdown', 'list'

        $switcher_html = $icl_language_switcher->wp_nav_menu_items_filter( '', (object) array() );

        switch($switcher_type) {
            case 'dropdown':
                $switcher_html = preg_replace(
                    array(
                        '/<a href="#" onclick="return false">(<)?([^<]+)<\/a>/',
                        '/sub-menu submenu-languages/'
                    ),
                    array(
                        '<a class="cursor-default">$1$2</a><a href="#language" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="true"><span class="caret"></span></a>',
                        'dropdown-menu submenu-languages'
                    ), $switcher_html);
                break;
            case 'list':
                $active_class = 'menu-item-language-current';
                $switcher_html = str_replace($active_class, 'active ' . $active_class, $switcher_html);
                break;
        }

        $html->text($switcher_html);
    }

    return $html;
});