!function (window, $) {
    'use strict';

    var document = window.document,
        body = document.body,
        isFrame = false,
        $window = $(window),
        $document = $(document),
        $html = $('html'),
        $body = $(body),

        userAgent = navigator.userAgent,

        isIEMobile = !!userAgent.match(/Windows Phone/i),
        isOSX = !!userAgent.match(/(iPad|iPhone|iPod|Macintosh)/g),

        $activeElement = $body,
        $root = (userAgent.indexOf('AppleWebKit') !== -1) ? $body : $html,

        options = {
            time: 300, // ms
            step: 300, // px

            // Easing
            easing: function (x) { return x; },

            // Acceleration
            accelerationDelta: 20,  // 20
            accelerationMax: 0.2,   // 1

            // Keyboard Settings
            arrowScroll: 50 // px
        },

        easeOptions = {
            pulse: {
                time: 900,
                step: 180
            },
            out2: {
                time: 500,
                step: 300
            },
            outBack: {
                time: 700,
                step: 350
            }
        },

        keyDelta;

    // Sign
    // ====

    var sign = Math.sign || function (value) {
        return value > 0 ? 1 : value < 0 ? -1 : 0;
    };

    // UID
    // ===

    var getUID = (function () {
        var uid = 0;

        return function ($element) {
            var data = $element.data();

            return data.uid || (data.uid = uid++);
        };
    })();


    // Request animation frame
    // =======================

    var requestFrame = function () {
        var fn = window.requestAnimationFrame,
            vendors,
            index,
            lastTime;

        if (!fn) {
            vendors = ['ms', 'moz', 'webkit', 'o'];

            for (index = 0; index < vendors.length && !fn; ++index) {
                fn = window[vendors[index] + 'RequestAnimationFrame'];
            }
        }

        if (!fn) {
            lastTime = 0;

            fn = function (callback) {
                var currentTime = new Date().getTime(),
                    timeToCall = Math.max(0, 16 - (currentTime - lastTime));

                lastTime = currentTime + timeToCall;

                return window.setTimeout(function () { callback(currentTime + timeToCall); }, timeToCall);
            };
        }

        return fn;
    }();

    // Cache
    // =====

    function Cache() {
        var cache = this;

        cache._ = {};

        setInterval(function () { cache._ = {}; }, 1e4);
    }

    Cache.prototype.get = function (uid) {
        return this._[uid];
    };

    Cache.prototype.update = function (value, $elements) {
        var _ = this._,
            index = $elements.length;

        while (index--) {
            _[getUID($elements.eq(index))] = value;
        }
    };

    // Overflow
    // ========

    var overflowCache = new Cache();

    function getOverflowAncestor($element) {
        //$element.parents;

        var rootScrollHeight = $root.prop('scrollHeight'),
            rootHeight = $root.height(),
            scrollHeight,
            $elements = $element,
            $ancestor,
            overflow;

        while ($element.length) {
            $ancestor = overflowCache.get(getUID($element));

            if (!$ancestor) {
                scrollHeight = $element.prop('scrollHeight');

                if (rootScrollHeight === scrollHeight) {
                    if (!isFrame || rootHeight + 10 < rootScrollHeight) {
                        $ancestor = $body; // scrolling root
                    }
                } else if ($element.height() + 10 < scrollHeight) {
                    overflow = $element.css('overflow-y');

                    if (overflow === "scroll" || overflow === "auto") {
                        $ancestor = $element;
                    }
                }
            }

            if ($ancestor) {
                overflowCache.update($ancestor, $elements);
                return $ancestor;
            }

            $elements.add($element = $element.parent());
        }

        if (!$ancestor) {
            overflowCache.update($body, $elements);
            return $body;
        }
    }


    // Easing
    // ======

    var easing = {
        pulse: function getPulseEase(pulseScale) {
            var pulseNormalize = 1,
                exp = Math.exp,
                coeff = exp(-1) - 1;

            function pulse(x) {
                x = x * pulseScale;

                return (x < 1 ? x - 1 + exp(-x) : 1 + exp(1 - x) * coeff) * pulseNormalize;
            }

            pulseNormalize /= pulse(1);

            return function (x) {
                return x >= 1 ? 1 : x <= 0 ? 0 : pulse(x);
            };
        }(4),

        out2: function (x) {
            return x * (2 - x);
        },

        outBack: function (x) {
            return (x -= 1) * x * (1.6 * x + 0.6) + 1;
        }
    };

    // Animate
    // =======

    var queue = [],
        pending = false,
        lastScroll = +new Date,
        direction = {
            x: 0,
            y: 0
        };

    function animate($element, deltaX, deltaY) {

        var element = $element.get(0),
            directionX = sign(deltaX),
            directionY = sign(deltaY),
            now = +new Date,
            elapsed,
            factor,
            scrollWindow;

        if (direction.x !== directionX || direction.y !== directionY) {
            direction.x = directionX;
            direction.y = directionY;
            queue = [];
            lastScroll = 0;
        }

        if (options.accelerationMax !== 1) {
            elapsed = now - lastScroll;

            if (elapsed < options.accelerationDelta) {
                factor = (1 + (30 / elapsed)) / 2;

                if (factor > 1) {
                    factor = Math.min(factor, options.accelerationMax);
                    deltaX *= factor;
                    deltaY *= factor;
                }
            }

            lastScroll = now;
        }

        queue.push({
            x: deltaX,
            y: deltaY,
            lastX: (deltaX < 0) ? 0.99 : -0.99,
            lastY: (deltaY < 0) ? 0.99 : -0.99,
            start: now
        });

        if (pending) {
            return;
        }

        scrollWindow = $element[0] === body;

        var animationStep = function () {

            var now = +new Date,
                elapsed,
                finished,
                progress,
                scrollX = 0,
                scrollY = 0,
                index,
                item,
                dx, dy;

            for (index = 0; index < queue.length; index++) {

                item = queue[index];
                elapsed = now - item.start;
                finished = (elapsed >= options.time);

                progress = (finished) ? 1 : elapsed / options.time;

                progress = options.easing(progress);

                dx = (item.x * progress - item.lastX) | 0;
                dy = (item.y * progress - item.lastY) | 0;

                scrollX += dx;
                scrollY += dy;

                item.lastX += dx;
                item.lastY += dy;

                if (finished) {
                    queue.splice(index, 1);
                    index--;
                }
            }

            if (scrollWindow) {
                window.scrollBy(scrollX, scrollY);
            } else {
                if (scrollX) {
                    element.scrollLeft += scrollX;
                }

                if (scrollY) {
                    element.scrollTop += scrollY;
                }
            }

            if (!deltaX && !deltaY) {
                queue = [];
            }

            if (queue.length) {
                requestFrame(animationStep);
            } else {
                pending = false;
            }
        };

        requestFrame(animationStep);
        pending = true;
    }

    function scroll (position) {
        animate($body, 0, position - $window.scrollTop());
    }

    // Handlers
    // ========

    function onMouseDown(event) {
        $activeElement = $(event.target);
    }

    function onWheel(event) {
        var deltaX = event.deltaX * options.step,
            deltaY = event.deltaY * options.step,
            $target = $(event.target),
            $overflowing = getOverflowAncestor($target);

        if (!$overflowing || event.defaultPrevented || ($activeElement.prop('nodeName') || '').toLowerCase() === 'embed') {
            return true;
        }

        animate($overflowing, -deltaX, -deltaY);

        return false;
    }

    function onKeyDown(event) {
        var target = event.target,
            nodeName = target.nodeName.toLowerCase(),
            keyCode = event.keyCode;

        if (event.defaultPrevented
            || /input|textarea|select|embed/i.test(nodeName)
            || target.isContentEditable
            || event.ctrlKey
            || event.altKey
            || event.metaKey
            || (event.shiftKey && keyCode !== 32)) {
            return true;
        }

        if (nodeName === 'button' && keyCode === 32) {
            return true;
        }

        var $element = getOverflowAncestor($activeElement);

        var pair = keyDelta[keyCode];

        if (!pair) {
            return true;
        }

        if ($.isFunction(pair)) {
            pair = pair(event, $element);
        }

        animate($element, pair[0], pair[1]);

        return false;
    }


    // Init
    // ====

    function getClientHeight($element) {
        return ($html.add($body).is($element) ? $window : $element).height();
    }

    function initKeys(){
        keyDelta = {
            // space
            32: function (event, $element) {
                return [0, (event.shiftKey ? -1 : 1) * getClientHeight($element) * 0.9];
            },

            // pageup
            33: function (event, $element) {
                return [0, -getClientHeight($element) * 0.9];
            },

            // pagedown
            34: function (event, $element) {
                return [0, getClientHeight($element) * 0.9];
            },

            // end
            35: function (event, $element) {
                var y = $element.prop('scrollHeight') - $element.scrollTop() - getClientHeight($element);
                return [0, y > 0 ? y + 10 : 0];
            },

            // home
            36: function (event, $element) {
                return [0, -$element.scrollTop()];
            },

            // left
            37: [-options.arrowScroll, 0],

            // up
            38: [0, -options.arrowScroll],

            // right
            39: [options.arrowScroll, 0],

            // down
            40: [0, options.arrowScroll]
        };
    }

    $(function () {
        var preset = $('meta[name=pagescroll]').prop('content');

        if (easing[preset]) {
            options = $.extend(options, easeOptions[preset]);
            options.easing = easing[preset];
        }

        $root = (navigator.userAgent.indexOf('AppleWebKit') === -1 || document.compatMode.indexOf('CSS') >= 0) ? $html : $body;

        isFrame = window.self !== window.top;

        if (preset && !Modernizr.touch && !isIEMobile && !isOSX) {
            initKeys();

            $window
                .on('mousedown', onMouseDown)
                .on('mousewheel', onWheel)
                .on('keydown', onKeyDown);
        }
    });

    $.scroll = scroll;

}(window, jQuery);