+function (factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD. Register as an anonymous module.
        define(['jquery'], factory);
    } else {
        // Browser globals
        factory(jQuery);
    }
}(function ($) {
    'use strict';

    var namespace = 'b3e',
        name = 'viewport-size',
        fnName = $.camelCase(name),
        dataName = namespace + '-' + name,

        old = $.fn[name],

        $window = $(window),
        windowWidth = $window.width(),
        isMobile = window.isMobile ? isMobile.any : ($('meta[name=ismobile]').prop('content') == 'true'),

        propertyNames = ['height', 'minHeight', 'maxHeight', 'width', 'minWidth', 'maxWidth'],
        dataNames = ['vh', 'vhmin', 'vhmax', 'vw', 'vwmin', 'vwmax'],
        cssViewportSizeSupport = testCssViewportSize(propertyNames);

    function testCssViewportSize(properties) {
        var support = {},
            test = {
                h: 'height',
                w: 'width'
            },
            dimension,
            index = properties.length,
            property,
            unit,

            userAgent = window.navigator.userAgent,

            isIOS7 = /(iPhone|iPod|iPad).+AppleWebKit/i.test(userAgent) && (function () {
                    var iOSversion = userAgent.match(/OS (\d)/);
                    return iOSversion && iOSversion.length > 1 && parseInt(iOSversion[1]) < 8;
                })(),


            $test = $('<div>').css({
                position: 'absolute',
                top: -2000,
                left: -2000,
                opacity: 0
            }).appendTo('body');

        while (index--) {
            property = properties[index];
            unit = property.slice(-1) == 't' ? 'h' : 'w';

            if ((isMobile || isIOS7) && unit == 'h') {
                support[property] = false;
            } else {
                dimension = test[unit];

                $test.css(property, '1v' + unit);

                support[property] = property.substr(0, 3) == 'max'
                    ? $test.css(dimension, 1000)[dimension]() < 1000
                    : $test[dimension]() > 0;

                $test.css(property, '').css(test[unit], '');
            }
        }

        $test.remove();

        return support;
    }

    function resize(data) {
        var $this = data.$this,
            properties = {},
            index = propertyNames.length,
            name,
            value,
            main,
            unit,
            offset,
            info = $this.data('om-viewport-info'),
            width,
            height;

        if (!info) {
            $this.data('om-viewport-info', info = {width: $this.width(), height: $this.height()});
        }

        while (index--) {
            name = propertyNames[index];
            value = data[name];

            if (value != undefined) {
                main = name.match(/height|width/i)[0].toLowerCase();
                unit = 'v' + main[0];
                offset = data[name + 'Offset'];
                offset = ($.isNumeric(offset) ? offset : $(offset)[main]()) | 0;

                properties[name] = value === '' ? '' : (cssViewportSizeSupport[name] && !offset) ? value + unit : $window[main]() * value / 100 - offset;
            }
        }

        $this.css(properties);

        width = $this.width();
        height = $this.height();

        info.deltaW = width - info.width;
        info.deltaH = height - info.height;
        info.width = width;
        info.height = height;

        $this.trigger('om-viewport-resize', [info]);
    }

    function onResize(e) {
        var width = $window.width();

        if (width !== windowWidth) {
            resize(e.data);
        }

        windowWidth = width;
    }

    $.fn[fnName] = function (options) {
        return this.each(function () {
            var $this = $(this),
                data = $this.data(dataName);

            if (!data) {
                data = $.extend({
                    $this: $this
                }, options);

                //..

                $this.data(dataName, data);

                resize(data);

                $window.on('resize.' + namespace + '.' + name, data, onResize);
            } else {
                $.extend(data, options);

                resize(data);
            }
        });
    };

    // NO CONFLICT
    // ===============

    $.fn[fnName].noConflict = function () {
        $.fn[fnName] = old;
        return this;
    };

    // DATA-API
    // ============

    (function () {
        var index = dataNames.length,
            dataName,
            propertyName;

        while (index--) {
            dataName = dataNames[index];
            propertyName = propertyNames[index];

            $('[data-' + dataName + ']').each(function () {
                var $this = $(this),
                    options = {};

                options[propertyName] = $this.data(dataName);
                options[propertyName + 'Offset'] = $this.data(dataName + '-offset');

                $this[fnName](options);
            });
        }
    })();
});