!function ($) {
    'use strict';

    var breakpoints = [480, 768, 992, 1320],
        
        $window = $(window);
    
    function setHeight($element, heights) {
        var width = $window.width(),
            length = Math.min(breakpoints.length, heights.length),
            breakpoint = length,
            index;

        for (index = 0; index < length; index++) {
            if (breakpoints[index] > width) {
                breakpoint = index;
                break;
            }
        }

        $element.height(heights[breakpoint] || heights[heights.length - 1]);
    }

    function setRatio($item, ratio) {
        $item.height($item.width() / ratio);
    }

    $('[data-om-slider]').each(function () {
        var $slider = $(this),
            speed = $slider.data('speed') || 200,
            height = $slider.data('height'),
            autoHeight,
            ratio;

        if (height === 'auto') {
            autoHeight = true;
        } else if ($.isNumeric(height)) {
            ratio = height;
        } else if (/^\d+\/\d+$/.test(height)) {
            ratio = height.split('/');
            ratio = parseInt(ratio[0]) / parseInt(ratio[1]);
        } else if (height) {
            $slider.addClass('unify').height(height);

            height = height.split(',');

            setHeight($slider, height);
            
            $window.on('resize', function () { setHeight($slider, height); });
        }

        if (ratio) {
            $slider.addClass('unify');

            setRatio($slider, ratio);

            $window.on('resize', function () { setRatio($slider, ratio); });
        }

        $slider.owlCarousel({
            pagination: !!$slider.data('pagination'),
            navigation: $slider.data('navigation'),
            navigationText: ['<a><span class="arrow-left"></span></a>', '<a><span class="arrow-right"></span></a>'],
            rewindNav: !!$slider.data('rewind'),
            singleItem: true,

            slideSpeed: speed,
            paginationSpeed: speed,
            rewindSpeed: $slider.data('rewind-speed') || 500,

            autoPlay: !!$slider.data('auto'),
            stopOnHover: !!$slider.data('onhover-stop'),

            autoHeight: autoHeight
        });
    });
}(jQuery);