<?php

function om_get_vc_params_script_url()
{
    static $url;

    if (empty($url)) {
        $url = get_template_directory_uri() . '/assets/js/admin/params.js';
    }

    return $url;
}

require_once __DIR__ . '/params/chosen.php';
require_once __DIR__ . '/params/clearfix.php';
require_once __DIR__ . '/params/inputs.php';
require_once __DIR__ . '/params/map.php';
require_once __DIR__ . '/params/number.php';
require_once __DIR__ . '/params/single_checkbox.php';

require_once __DIR__ . '/params-extends/google-fonts.php';
require_once __DIR__ . '/params-extends/iconpicker.php';