!function ($) {
    'use strict';

    $(function(){
        // Color Picker
        if (typeof $.wp !== 'undefined' && typeof $.wp.wpColorPicker !== 'undefined') {
            var $meta = $('meta[name="colorpicker"]'),
                meta;

            if ($meta.length && (meta = $meta.prop('content'))) {
                $.wp.wpColorPicker.prototype.options.palettes = meta.split(',');
            }
        }
    });
}(jQuery);