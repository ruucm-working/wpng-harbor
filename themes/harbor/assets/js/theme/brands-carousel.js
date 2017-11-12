!function ($) {
    'use strict';

    $('.brands-carousel').each(function () {
        var $brand = $(this);

        $brand.owlCarousel({
            autoPlay: 3000,
            pagination: !!$brand.data('pagination'),
            items: 5,
            itemsDesktop: [1199, 4],
            itemsDesktopSmall: [991, 3],
            stopOnHover: true
        });
    });
}(jQuery);