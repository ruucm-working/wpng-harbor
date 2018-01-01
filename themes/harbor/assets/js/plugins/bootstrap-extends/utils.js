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


    /*
     * Polifills
     */

    (function (window) {
        var vendors = ['o', 'moz', 'webkit', 'ms'],
            index = vendors.length,
            lastTime = 0;

        while (index-- && !window.requestAnimationFrame) {
            window.requestAnimationFrame = window[vendors[index] + 'RequestAnimationFrame'];
            window.cancelAnimationFrame = window[vendors[index] + 'CancelAnimationFrame'] || window[vendors[index] + 'CancelRequestAnimationFrame'];
        }

        if (!window.requestAnimationFrame)
            window.requestAnimationFrame = function (callback, element) {
                var currentTime = new Date().getTime(),
                    timeToCall = Math.max(0, 16 - (currentTime - lastTime)),
                    id = window.setTimeout(function () { callback(currentTime + timeToCall); }, timeToCall);

                lastTime = currentTime + timeToCall;

                return id;
            };

        if (!window.cancelAnimationFrame)
            window.cancelAnimationFrame = function (id) {
                clearTimeout(id);
            };

    }(window));


    /*
     * Common
     */

    function noConflict(name, old) {
        return function () {
            $.fn[name] = old;
            return this;
        }
    }


    /* 
     * Delayed plugin
     */

    var name = 'delayed',
        old = $.fn[name];

    $.fn[name] = function (fn) {
        var $this = this,
            parameters = Array.prototype.slice.call(arguments, 1);

        fn = $.isFunction(fn) ? fn : $.isFunction($this[fn]) ? $this[fn] : null;

        if (fn) {
            $this.queue(function (next) {
                fn.apply($this, parameters);

                next();
            });
        }

        return this;
    };

    // NO CONFLICT
    // ===========

    $.fn[name].noConflict = noConflict(name, old);


    /* 
     * Class toggle
     */

    function toggleClassHandler() {
        var data = $(this).data(),
            onDelay = data.onDelay,
            offDelay = data.offDelay,
            onClass = data.onClass,
            offClass = data.offClass,
            toggleClass = data.toggleClass,
            $target = $(data.target);

        if ($target.hasClass(toggleClass)) {
            if (offDelay) {
                $target
                    .addClass(offClass)
                    .delay(offDelay)
                    .delayed('removeClass', offClass + ' ' + toggleClass);
            } else {
                $target.removeClass(toggleClass);
            }
        } else {
            if (onDelay) {
                $target
                    .addClass(onClass)
                    .delay(onDelay)
                    .delayed('addClass', toggleClass)
                    .delayed('removeClass', onClass);
            } else {
                $target.addClass(toggleClass);
            }
        }
    }

    // DATA-API
    // ========

    $(document)
        .on('click.b3e.class.data-api', '[data-toggle-class]', toggleClassHandler);
});