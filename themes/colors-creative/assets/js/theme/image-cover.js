!function ($) {
    'use strict';

    var notObjectFit = Modernizr && !Modernizr.prefixed('objectFit');

    function coverFallback() {
        var $img = $(this).css('opacity', 0);

        $('<div class="img-cover">').css({
            'background': 'url(' + $img.prop('src') + ') no-repeat center',
            'background-size': 'cover'
        }).insertAfter($img).append($img);
    }

    $(function () {
        if (notObjectFit) {
            $('[data-image-cover]').each(function () {
                var $this = $(this);

                $this.imagesLoaded(function () {
                    $this.find('.img-cover').each(coverFallback);
                });
            });
        }
    });

}(jQuery);