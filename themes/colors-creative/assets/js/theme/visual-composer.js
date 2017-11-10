!function ($, window) {
    'use strict';

    var $window = $(window),
        $html = $('html'),
        $style = $('<style/>').appendTo('head'),
        threshold = 768,
        on = false,
        mobile = window.isMobile ? isMobile.any : ($('meta[name=ismobile]').prop('content') == 'true');

    function matchHeight() {
        var $row = $('.vc-row-match-height'),
            width = $window.width();

        if ($html.hasClass('no-flexboxlegacy')) {
            $row = $row.add('.vc-content-bottom,.vc-content-middle');
        }

        if (!on && width >= threshold) {
            $row.each(function () {
                $(this).children('.wpb_column').matchHeight();
            });
        } else if (on && width < threshold) {
            $row.children('.wpb_column').matchHeight('remove');
        }
    }

    function addVideoBackground() {
        var $row = $(this),
            youtubeUrl = $row.data('video-src'),
            youtubeId = vcExtractYoutubeId(youtubeUrl);

        if (youtubeId) {
            insertYoutubeVideoAsBackground($row, youtubeId);
        }

        $window.on('grid:items:added', function (event, $grid) {
            if (!$row.has($grid).length) {
                return;
            }

            vcResizeVideoBackground($row);
        });
    }

    function outlineStyles(dataName, properties, hover) {
        var selectors = {};

        return function () {
            var $this = $(this),
                color = $this.data(dataName),
                selector = '[data-' + dataName + '="' + color + '"]',
                styles,
                rule;

            if (!selectors[selector]) {
                selectors[selector] = true;

                styles = $.map(properties, function (property) {
                    return property + ':' + color + ';';
                }).join('');

                rule = selector;

                if(hover) {
                    rule += ':hover,' + selector + ':focus';
                }

                rule += '{' + styles + '}';

                $style.text($style.text() + rule);
            }
        };
    }

    $(function () {
        matchHeight();
        $window.on('resize', matchHeight);

        if (mobile) {
            $('html').addClass('is-mobile-any');
        }

        if ('vcExtractYoutubeId' in window) {
            $('.section-background-video[data-video-src]').each(addVideoBackground);
        }
    });

    $('[data-om-vc-outline-color]').each(outlineStyles('om-vc-outline-color', ['border-color', 'color']));
    $('[data-om-vc-outline-hover-bg]').each(outlineStyles('om-vc-outline-hover-bg', ['background-color', 'border-color'], true));
    $('[data-om-vc-outline-hover-text]').each(outlineStyles('om-vc-outline-hover-text', ['color'], true));

}(jQuery, window);