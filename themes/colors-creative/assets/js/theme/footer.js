!function ($) {

    $(function () {
        if (Modernizr.flexboxlegacy || !Modernizr.csstransforms) {
            return;
        }

        var $footer = $('.content-info'),
            $window = $(window),
            shift = 0,

            makeSticky = function () {
                var diff = $window.height() - $footer.offset().top - $footer.outerHeight(),
                    transform = '';

                if (diff > 0 || shift > 0) {
                    shift = Math.max(0, shift + diff);
                    transform = 'translateY(' + shift + 'px)';
                }

                $footer.css('transform', transform);
            };

        if($footer.length) {
            makeSticky();
            setInterval(makeSticky, 500);
        }
    });
}(jQuery);