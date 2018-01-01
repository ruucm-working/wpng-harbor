!function ($) {
    'use strict';

    var $body = $('body');

    // Add to cart
    // ===========

    function updateQuantity(delta, dataName) {
        return function (index, element) {
            var $quantity = $(element),
                quantity = ($quantity.data(dataName) || 0) + delta;

            $quantity.data(dataName, quantity);

            $quantity
                .removeClass('added')
                .text(quantity)
                .delay(50)
                .delayed('addClass', 'added');


        }
    }

    $body
        .on('adding_to_cart', function (event, $button) {
            $button
                .clearQueue()
                .removeClass('add-success');
        })
        .on('added_to_cart', function (event, fragments, cart_hash, $button) {
            var productId = $button.data('product_id'),
                quantity = $button.data('quantity') || 1;

            $button
                .addClass('add-success')
                .delay(2000)
                .delayed('removeClass', 'add-success');

            $button.next('.added_to_cart').remove();

            $('[data-product-quantity][data-product=' + productId + ']').each(updateQuantity(quantity, 'productQuantity'));
            $('[data-wc-cart-count]').each(updateQuantity(quantity, 'wcCartCount'));
        })
        .on('click', '[data-toggle=quantity]', function (e) {
            var $this = $(this),
                data = $this.data(),
                $target,
                max,
                min,
                step,
                value;

            if (!data.$target) {
                data.$target = $this.parent().siblings('[data-quantity]');
            }

            $target = data.$target;
            max = parseFloat($target.prop('max')) || false;
            min = parseFloat($target.prop('min')) || 0;
            step = parseFloat($target.prop('step')) || 1;
            value = (parseFloat($target.val()) || 0) + (parseFloat(data.value) || 0) * step;

            if (max && value > max) {
                value = max;
            } else if (value < min) {
                value = min;
            }

            $target.val(value);

            // Trigger change event
            $target.trigger('change');
        });

    // Variations
    // ==========

    function updateVariationData($image, data) {
        var $wrapper = $image.parent(),
            slider = $wrapper.data('slider');

        data = data ? data : $wrapper.data('defaultVariation');

        $wrapper.data('title', data.title);
        $wrapper.data('size', data.size);
        $wrapper.data('item', data.item);
        $wrapper.attr('href', data.item);

        if (!slider) {
            slider = $wrapper.parents('[data-om-slider]').data('owlCarousel');
            $wrapper.data('slider', slider);
        }

        slider.goTo(0);
    }

    $('.variations_form')
        .each(function () {
            var $form = $(this),
                $product = $form.closest('.product'),
                $images = $product.find('.images'),
                $photoswipe = $images.find('[data-om-photoswipe]'),
                $image = $images.find('img:eq(0)'),
                $wrapper = $image.parent();

            if (!$photoswipe.length) {
                return;
            }

            $wrapper.data('defaultVariation', $.extend({}, $wrapper.data()));

            $form.on('wc_variation_form', function () {
                $form
                    .on('found_variation', function (event, variation) {
                        updateVariationData($images.find('img:eq(0)'), {
                            item: variation.image_link,
                            title: variation.image_title,
                            size: variation.image_link_width + 'x' + variation.image_link_height
                        });
                    })
                    .on('reset_data', function () {
                        updateVariationData($images.find('img:eq(0)'));
                    });
            });
        })
        .each(function () {
            $(this).on('change', '.variations select', function () {
                var $this = $(this),
                    action = ($this.val() ? 'add' : 'remove') + 'Class';

                $this[action]('option-selected');
            });
        });

    // Fix of dropdown product cat widget
    $('select.dropdown_product_cat').addClass('form-control');

    // Fix of price filter widget
    $('.price_slider_wrapper .button').removeClass('button').addClass('btn btn-sm btn-flat btn-default');

}(jQuery);