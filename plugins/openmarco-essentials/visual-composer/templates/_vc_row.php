<?php
use Essentials\Data\Options;
use Essentials\Html\Writer;

/**
 * @var array $atts
 */

$current_template_path = ((isset($atts['section_enable']) && $atts['section_enable'] === 'disable')
|| (isset($atts['disable_element']) && $atts['disable_element'] === 'yes'))
    ? Vc_Manager::getInstance()->path('TEMPLATES_DIR', 'shortcodes/vc_row.php')
    : __DIR__ . '/_vc_section_row.php';

include $current_template_path;