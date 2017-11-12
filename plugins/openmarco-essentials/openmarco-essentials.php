<?php

/*
Plugin Name: Colors Creative (Openmarco) Essentials
Plugin URI: http://openmarco.com/
Description: Colors Creative necessary tools.
Version: 1.1.3
Author: Openmarco
Author URI: http://openmarco.com/
License: General Public License
*/

use Essentials\Core\Config;
use Essentials\Core\Context;
use Essentials\Core\Framework;

define('OM_COLORS_CREATIVE_ESSENTIALS', '1.1.3');

require_once __DIR__ . '/inc/autoload.php';

spl_autoload_register(function ($class) {
    $in = array('\\', '_', 'essentials');
    $out = array('/', '-', 'lib');
    $file = __DIR__ . '/' . str_replace($in, $out, strtolower($class)) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

function openmarco_essentials_init()
{
	if (defined('WPB_VC_VERSION')) {
		require_once __DIR__ . '/visual-composer/visual-composer.php';
	}
	
    $context = new Context();
    $config = new Config($context);
    $config->read(array('common', 'customizer', 'options', 'post_meta', 'post_types', 'taxonomies', 'layouts', 'assets'));

    Framework::create($context, $config);
}
