!function ($, Modernizr, FastClick) {

    var mobile = window.isMobile ? isMobile.any : ($('meta[name=ismobile]').prop('content') == 'true');
        userAgent = navigator.userAgent,

        $html = $('html'),
        $body = $(document.body);

    if(mobile && userAgent.match(/iP(hone|ad)/) && userAgent.match(/Safari/) && !$body.children('.content-info').length) {
        $html.addClass('is-ios-safari');
    }

    // if IE Mobile
    if(mobile && userAgent.match(/IEMobile/)) {
        Modernizr.flexboxlegacy = false;

        $html.removeClass('flexboxlegacy').addClass('no-flexboxlegacy');
    }

    //console.log(FastClick.prototype.needsClick);



    if(mobile && FastClick && ($('meta[name=isfastclick]').prop('content') !== 'true')) {
        FastClick.prototype._needsClick = FastClick.prototype.needsClick;
        FastClick.prototype.needsClick = function(target) {
            if((/select2-cho/).test(target.className)){
                return true;
            }

            return this._needsClick(target);
        };

        $(function() {
            FastClick.attach(document.body);
        });
    }
}(jQuery, Modernizr, FastClick);