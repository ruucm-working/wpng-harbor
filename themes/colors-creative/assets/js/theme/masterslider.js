!function (window) {
    'use strict';

    var mastersliders = window.masterslider_instances = window.masterslider_instances || [];

    mastersliders.push = function () {
        Array.prototype.push.apply(this, arguments);

        msAfterInit(arguments);
    };

    function msAfterInit(sliders) {
        for (var index = 0, length = sliders.length; index < length; index++) {
            sliders[index].api.addEventListener(MSSliderEvent.INIT, function (event) {
                var slider = event.target.slider;

                if (slider._cc || !slider.$element.hasClass('ms-skin-colors-creative') || !slider.$controlsCont.length) {
                    return
                }

                slider._cc = true;

                slider.$controlsCont
                    .find('.ms-nav-prev').html('<span class="arrow-left">').end()
                    .find('.ms-nav-next').html('<span class="arrow-right">');
            });
        }
    }
}(window);