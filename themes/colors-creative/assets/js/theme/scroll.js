!function ($, window) {
    'use strict';

    var document = window.document,
        $window = $(window),
        $document = $(document),
        $body = $('body'),
        $colorTest = $('<div>').css({
            display: 'none',
            opacity: 0,
            width: 0,
            position: 'absolute',
            left: -2000
        }).appendTo($body),
        rgbaDictionary = {},
        mobile = window.isMobile ? isMobile.any : ($('meta[name=ismobile]').prop('content') == 'true');

    function getRGBA(color) {
        if (rgbaDictionary[color]) {
            return rgbaDictionary[color];
        }

        var rgba = $colorTest.css('color', color).css('color').match(/^rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*(\d+(?:\.\d+)?))?\)$/);
        rgba.shift();
        rgba[3] = rgba[3] || 1;

        return rgbaDictionary[color] = rgba.map(Number);
    }

    function getOffset(offset, $element) {
        var coeff;

        if (offset && offset.toString().indexOf('height') >= 0) {
            coeff = offset === 'halfheight' ? 0.5 : 1;

            offset = -($element.position().top + $element.height()) * coeff - 1;
        }

        return offset;
    }

    function fade(color, alpha) {
        color = getRGBA(color);
        color[3] = alpha === undefined ? 1 : alpha;
        return format('rgba($1,$2,$3,$4)', color);
    }

    function isDark(color) {
        color = getRGBA(color);

        return (color[0] * 299 + color[1] * 587 + color[2] * 114) / 1000 < 125;
    }

    function scrollBackgroundChange($target, value, toggleFn, toggleClass, removeClass) {
        $target.css('background-color', value)
            .trigger('backgroundcolorchange', [value])
            .removeClass(removeClass)[toggleFn](toggleClass)
            .trigger('classchange');
    }

    function onTrigger($target, color, alpha, mode) {
        var index = isDark(color) ? 1 : 0,
            style = ['light', 'dark'],
            value = mode === 'add' ? color : '',
            toggleFn = mode + 'Class',
            additional = 'background-',
            removeClass = additional + style[Math.abs(index - 1)],
            toggleClass = additional + style[index],
            fn;

        if (mode === 'add' && $.isArray(alpha)) {
            value = [];
            for (index = 0; index < $target.length; index++) {
                value[index] = fade(color, alpha[index]);
            }

            fn = function () {
                var index = $target.length;
                while (index--) {
                    scrollBackgroundChange($target.eq(index), value[index], toggleFn, toggleClass, removeClass);
                }
            };
        } else {
            fn = function () {
                scrollBackgroundChange($target, value, toggleFn, toggleClass, removeClass);
            };
        }

        return fn;
    }

    function parseScrollAnimate(controller, $element) {
        var element = $element.get(0);

        return function (item, key) {
            var matches = key.match(/^(100|[0-9]{1,2})(_(100|[0-9]{1,2}))?$/),
                scene,
                hook,
                offset;

            if (matches) {
                hook = 1 - (matches[3] ? parseInt(matches[3]) : 0) / 100;
                offset = parseInt(matches[1]) / 100 * $element.height();

                scene = new ScrollMagic.Scene({
                    triggerElement: element,
                    triggerHook: hook,
                    offset: offset
                })
                    .addTo(controller);

                scene.data = matches[0];

                return scene;
            }
        };
    }

    function getOrderScenes(vh) {
        return function orderScenes(a, b) {
            a = a.triggerPosition() - a.triggerHook() * vh;
            b = b.triggerPosition() - b.triggerHook() * vh;

            return a > b ? 1 : a < b ? -1 : 0;
        };
    }

    function format(string, args) {
        return string.replace(/\$(\d)/g, function (match, number) {
            return typeof args[--number] != 'undefined' ? args[number] : match;
        });
    }

    function parseAnimateStyles(string) {
        var strings = string.split(';'),
            param,
            styles = {},
            index;

        for (index = 0; index < strings.length; index++) {
            param = strings[index].split(':');
            styles[param[0]] = param[1] || '$1';
        }

        return styles;
    }

    function parseAnimateValues(styles, string) {
        var strings = string.toString().split(';'),
            values = {},
            key,
            index = 0;

        for (key in styles) {
            values[key] = $.map(strings[index].split(','), parseFloat);
            index++;
        }

        return values;
    }

    function getArrayProgress(progress, start, end) {
        var index = start.length,
            values = [];

        while (index--) {
            values[index] = end[index] * progress + start[index] * (1 - progress);
        }

        return values;
    }

    function onAnimateProgress($element, styles, start, end) {
        return function (event) {
            var progress = event.progress,
                key,
                values = {};

            for (key in styles) {
                values[key] = format(styles[key], getArrayProgress(progress, start[key], end[key]));
            }

            $element.css(values);
        };
    }

    function setHash(hash, $elements) {
        var dataTempAtts = '_temp_atts';

        $elements.each(function () {
            var $this = $(this);

            $this.data(dataTempAtts, {
                id: $this.attr('id'),
                name: $this.attr('name')
            });
            $this.attr('id', '').attr('name', '');
        });

        document.location.hash = hash;

        $elements.each(function () {
            var $this = $(this);

            $this.attr($this.data(dataTempAtts));
            $this.removeData(dataTempAtts);
        });
    }

    function getScrollFn(controller, shift) {
        return function (position, instant) {
            if (!$.isNumeric(position)) {
                if (position.substr(0, 2) === '#&') {
                    return false;
                }

                var selector = position[0] === '#' ? (position + ',[name="' + position.substr(1) + '"]') : position,
                    $elements = $(selector),
                    offset = $elements.offset();

                if (offset) {
                    if (position[0] === '#') {
                        setHash(position, $elements);
                    }

                    position = Math.max(offset.top - shift, 0);
                } else {
                    return false;
                }
            }

            controller.scrollTo(position, instant);

            return true;
        }
    }

    $(function () {
        var controller = new ScrollMagic.Controller(),
            $navigation = $('#navigation'),
            navmenuOffset = $navigation.offset(),
            scrollTo = getScrollFn(controller, ($navigation.height() || 0) + (navmenuOffset ? navmenuOffset.top : 0));

        controller.scrollTo(function (position, instant) {
            if (instant) {
                $window.scrollTop(position);
            } else {
                $.scroll(position);
            }
        });

        $('[data-scroll-bg]').each(function () {
            var element = this,
                $element = $(element),
                $childTarget = $element.find($element.data('scroll-child-target')),
                $target = $($element.data('scroll-target') || 'body').add($childTarget),
                colors = $element.data('scroll-bg').split(';'),
                changeColor = [],
                index = colors.length,
                hook = $element.data('scroll-hook'),
                offset = $element.data('scroll-offset'),
                alpha = $element.data('scroll-alpha'),
                scene;

            if ($target.length) {

                scene = new ScrollMagic.Scene({
                    triggerElement: element,
                    triggerHook: hook === undefined ? 0.5 : hook,
                    duration: $.proxy($element.height, $element),
                    offset: getOffset(offset, $target)
                }).addTo(controller);

                while (index--) {
                    changeColor[index] = onTrigger($target, colors[index], alpha, 'add');
                }

                if (colors.length > 1) {
                    scene
                        .on('enter', function () {
                            $element.data('color-interval', setInterval(function () {
                                var index = ($element.data('color-index') || 0) + 1;

                                $target.css('transition', 'all 3s linear');

                                if (index > colors.length - 1) index = 0;
                                $element.data('color-index', index);

                                changeColor[index]();

                            }, 3000));
                        })
                        .on('leave', function () {
                            $target.css('transition', '');
                            clearInterval($element.data('color-interval'));
                            $element.data('color-index', null);
                        });
                }

                scene
                    .on('enter', changeColor[0])
                    .on('leave', onTrigger($target, colors[0], alpha, 'remove'));
            }
        });

        $document
            .on('click', '[data-scroll-to]', function (event) {
                if (scrollTo($(this).data('scroll-to'))) {
                    event.preventDefault();
                }
            })
            .on('click', '.navmenu-nav .nav a:first-child', function (event) {
                var link = $(this).prop('href').replace(location.origin + location.pathname, ''),
                    isScroll;

                if (link[0] === '#') {
                    isScroll = scrollTo(link === '#' ? 0 : link);

                    if (isScroll) {
                        event.preventDefault();
                    }

                    if (isScroll && $navigation.hasClass('in')) {
                        $navigation.find('.navmenu-toggle').trigger('click');
                    }
                }

            })
            .on('click', '.wrap a, .content-info a', function (event) {
                var $this = $(this),
                    link;

                if (!$this.parents('.vc_tta-panel-heading,.vc_tta-tabs-container,.vc_pagination').length) {

                    link = $this.prop('href').replace(location.origin + location.pathname, '');

                    if (link[0] === '#' && scrollTo(link === '#' ? 0 : link)) {
                        event.preventDefault();
                    }
                }
            })
            .on('classchange', '[data-classchange]', function () {
                var $this = $(this),
                    $target = $($this.data('classchange-target')),
                    values = $this.data('classchange').split(' '),
                    index = values.length,
                    value;

                while (index--) {
                    value = values[index];
                    $target[($this.hasClass(value) ? 'add' : 'remove') + 'Class'](value);
                }
            });


        if (!mobile) {
            $('[data-scroll-animate]').each(function () {
                var $this = $(this),
                    triggerSelector = $this.data('scroll-trigger'),
                    $triggerElement = triggerSelector ? $this.parents(triggerSelector).first() : $this,
                    scenes = $.map($this.data(), parseScrollAnimate(controller, $triggerElement)),
                    scene,
                    nextScene,
                    index = scenes.length,
                    styles,
                    values = [],
                    vh;

                if (index) {
                    vh = $window.height();

                    scenes = scenes.sort(getOrderScenes(vh));
                    styles = parseAnimateStyles($this.data('scroll-animate'));

                    while (index--) {
                        values[index] = parseAnimateValues(styles, $this.data(scenes[index].data));
                    }

                    index = scenes.length - 1;

                    while (index--) {
                        scene = scenes[index];
                        nextScene = scenes[index + 1];
                        scene.duration(nextScene.triggerPosition() - nextScene.triggerHook() * vh - scene.triggerPosition() + scene.triggerHook() * vh);

                        scene.on('progress', onAnimateProgress($this, styles, values[index], values[index + 1]));
                    }
                }

            });
        }

        if (document.location.hash.length > 1) {
            setTimeout(function () {
                scrollTo(document.location.hash, true);
                $('.navmenu-nav .nav a[href="' + document.location.hash + '"]').parent().addClass('active');
            }, 100);
        }
    });
}(jQuery, window);