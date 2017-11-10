<?php

add_filter('vc_google_fonts_get_fonts_filter', function ($fonts_list) {

    static $full;

    if(empty($full)) {
        $full = array();
        $current = from($fonts_list)->toDictionary('$v->font_family', '$v')->toArray();
        $source = titan_get_googlefonts();

        $type_defaults = array(
            '100' => '100 light regular:100:normal',
            '100italic' => '100 light italic:100:italic',
            '200' => '200 light regular:200:normal',
            '200italic' => '200 light italic:200:italic',
            '300' => '300 light regular:300:normal',
            '300italic' => '300 light italic:300:italic',
            '400' => '400 regular:400:normal',
            'italic' => '400 italic:400:italic',
            '500' => '500 bold regular:500:normal',
            '500italic' => '500 bold italic:500:italic',
            '600' => '600 bold regular:600:normal',
            '600italic' => '600 bold italic:600:italic',
            '700' => '700 bold regular:700:normal',
            '700italic' => '700 bold italic:700:italic',
            '800' => '800 bold regular:800:normal',
            '800italic' => '800 bold italic:800:italic',
            '900' => '900 bold regular:900:normal',
            '900italic' => '900 bold italic:900:italic'
        );

        foreach($source as $item) {
            $name = $item['name'];

            if(isset($current[$name])) {
                $font = $current[$name];
            } else {
                $styles = array();
                $types = array();

                foreach($item['variants'] as $variant) {
                    $styles[] = $variant == '400' ? 'regular' : $variant;
                    $types[] = $type_defaults[$variant];
                }

                $font = (object) array(
                    'font_family' => $name,
                    'font_styles' => join(',', $styles),
                    'font_types' => join(',', $types)
                );
            }

            $full[] = $font;
        }
    }

    return $full;
});