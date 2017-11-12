<?php

require_once __DIR__ . '/widgets/om-social-icons-widget.php';

add_action('widgets_init', function () {
    register_widget('OM_Social_Icons_Widget');
});

add_filter('widget_text', 'do_shortcode');

add_filter('get_calendar', function ($html) {
    return str_replace('<table', '<table class="table"', $html);
});