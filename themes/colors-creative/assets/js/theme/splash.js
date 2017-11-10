!function ($) {
    'use strict';

    var $document = $(document),
        $body = $('body'),
        location = window.location,
        hideTimer,
        url = location.origin + location.pathname,
        $splash = $('[data-om-splash]');

        

    function isCurrentPage($link) {
        var href = $link.prop('href');

        return $link.data('toggle') || !href || href.replace(url, '')[0] === '#';
    }

    function hideSplash() {
        clearTimeout(hideTimer);

        $splash
            .delay(400)
            .delayed('addClass', 'loaded')
            .delayed(function () { $body.addClass('load'); })
            .delay($splash.data('omSplash') || 400)
            .delayed('addClass', 'hidden');
    }

    function showSplash(e) {
        clearTimeout(hideTimer);

        var $target = $(e.currentTarget);

        if (isCurrentPage($target)) {
            return;
        }

        if ($target.parents('.navmenu-nav').length) {
            $('.navmenu-toggle').trigger('click');
        }

        $body.removeClass('load');

        $splash
            .clearQueue()
            .removeClass('hidden')
            .delay(50)
            .delayed('removeClass', 'loaded');
    }

    var LoadingHooksCase = new LoadingHooks(),
        imagesHook = LoadingHooksCase.makeHookEach(),
        timoutHook = LoadingHooksCase.makeHookAny();

    $body.imagesLoaded($.proxy(imagesHook.resolve, imagesHook));
    setTimeout(timoutHook.resolve, 7e3);

    $.newLoadingPromise = function() {
        return LoadingHooksCase.makeHookEach();
    };

    $(function(){
        LoadingHooksCase.whenReady(hideSplash);

        $document.on('click', '[data-om-splash-on], .navmenu-nav a, .navmenu-brand', showSplash);
    });

} (jQuery);