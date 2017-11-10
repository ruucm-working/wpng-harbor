jQuery(function ($) {

    Color.prototype.toString = function (remove_alpha) {
        if (remove_alpha == 'no-alpha') {
            return this.toCSS('rgba', '1').replace(/\s+/g, '');
        }
        if (this._alpha < 1) {
            return this.toCSS('rgba', this._alpha).replace(/\s+/g, '');
        }
        var hex = parseInt(this._color, 10).toString(16);
        if (this.error) return '';
        if (hex.length < 6) {
            for (var i = 6 - hex.length - 1; i >= 0; i--) {
                hex = '0' + hex;
            }
        }
        return '#' + hex;
    };

    $('[data-color-advanced]').each(function () {
        var $control = $(this),
            alpha = $control.data('color-advanced'),
            value = $control.val().replace(/\s+/g, '');

        if (!alpha) {
            $control.wpColorPicker();
        } else {
            $control.wpColorPicker({ // change some things with the color picker
                clear: function (event, ui) {
                    // TODO reset Alpha Slider to 100
                    if (wp.customize) {
                        setTimeout(function () {
                            // send ajax request to wp.customizer to enable Save & Publish button
                            var _new_value = $control.val();

                            var key = $control.data('customize-setting-link');
                            wp.customize(key, function (obj) {
                                obj.set(_new_value);
                            });
                        }, 300);
                    }
                },
                change: function (event, ui) {
                    if (wp.customize) {
                        setTimeout(function () {
                            // send ajax request to wp.customizer to enable Save & Publish button
                            var _new_value = $control.val();

                            var key = $control.data('customize-setting-link');
                            wp.customize(key, function (obj) {
                                obj.set(_new_value);
                            });
                        }, 300);
                    }

                    // change the background color of our transparency container whenever a color is updated
                    var $transparency = $control.parents('.wp-picker-container:first').find('.transparency');
                    // we only want to show the color at 100% alpha
                    $transparency.css('backgroundColor', ui.color.toString('no-alpha'));
                }//,
                //palettes: palette // remove the color palettes
            });

            $('<div class="pluto-alpha-container"><div class="slider-alpha"></div><div class="transparency"></div></div>').appendTo($control.parents('.wp-picker-container'));

            var $alpha_slider = $control.parents('.wp-picker-container:first').find('.slider-alpha'),
                alpha_val;
            // if in format RGBA - grab A channel value

            if (value.match(/rgba\(\d+\,\d+\,\d+\,([^\)]+)\)/)) {
                alpha_val = parseFloat(value.match(/rgba\(\d+\,\d+\,\d+\,([^\)]+)\)/)[1]) * 100;
                alpha_val = parseInt(alpha_val);
            } else {
                alpha_val = 100;
            }

            $alpha_slider.slider({
                slide: function (event, ui) {
                    $(this).find('.ui-slider-handle').text(ui.value); // show value on slider handle

                    if (wp.customize) {
                        // send ajax request to wp.customizer to enable Save & Publish button
                        var _new_value = $control.val();
                        var key = $control.attr('data-customize-setting-link');
                        wp.customize(key, function (obj) {
                            obj.set(_new_value);
                        });
                    }
                },
                create: function (event, ui) {
                    var v = $(this).slider('value');
                    $(this).find('.ui-slider-handle').text(v);
                },
                value: alpha_val,
                range: "max",
                step: 1,
                min: 0,
                max: 100
            }); // slider

            $alpha_slider.slider().on('slidechange', function (event, ui) {
                var new_alpha_val = parseFloat(ui.value),
                    iris = $control.data('a8cIris'),
                    color_picker = $control.data('wpWpColorPicker');

                iris._color._alpha = new_alpha_val / 100.0;

                $control.val(iris._color.toString());

                color_picker.toggler.css({
                    backgroundColor: $control.val()
                });

                // fix relationship between alpha slider and the 'side slider not updating.
                var get_val = $control.val();

                $($control).wpColorPicker('color', get_val);
            });
        }
    }); // each

});