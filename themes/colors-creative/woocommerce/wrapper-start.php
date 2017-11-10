<?php
use Essentials\Data\Options;

global $fallback;

if ($fallback) {
    $section_class = '';
    $section_atts = '';
} else {
    $id = om_get_current_page_id();
    $section_class = Options::get('content_section_size');
    $section_atts = om_get_shifting_attributes('', $id);
}
?>
<div class="section <?php echo esc_attr($section_class) ?>"<?php om_attributes_string($section_atts) ?>>
    <div class="container">
        <div class="section-content">