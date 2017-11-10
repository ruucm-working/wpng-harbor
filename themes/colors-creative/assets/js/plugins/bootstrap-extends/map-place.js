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
        name = 'map-place',
        fnName = $.camelCase(name),
        dataName = namespace + '-' + name,

        old = $.fn[name];

    function init(data) {
        var latLng,
            map,
            marker;

        latLng = new google.maps.LatLng(data.ll[0], data.ll[1]);

        map = new google.maps.Map(data.$map.get(0), {
            zoom: data.zoom,
            center: latLng,
            disableDefaultUI: true,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            backgroundColor: data.bg,

            scrollwheel: data.scrollwheel,
            draggable: data.draggable,

            zoomControl: $.inArray('zoom', data.controls) > -1,
            panControl: $.inArray('pan', data.controls) > -1,
            mapTypeControl: $.inArray('type', data.controls) > -1,
            scaleControl: $.inArray('scale', data.controls) > -1,
            streetViewControl: $.inArray('street', data.controls) > -1,
            rotateControl: $.inArray('rotate', data.controls) > -1,
            overviewMapControl: $.inArray('overview', data.controls) > -1            
        });

        if(data.styles) map.setOptions({ styles: data.styles });

        marker = new google.maps.Marker({
            map: map,
            position: latLng,
            //title: '',
            icon: data.icon
        });
    }

    $.fn[fnName] = function (options) {
        return this.each(function () {
            var $this = $(this),
                data = $this.data(dataName);

            if (!data) {
                var controls = ($this.data('map-controls') || '').split(',');
                
                data = $.extend({
                    $map: $this,
                    ll: $this.data(name),
                    zoom: $this.data('map-zoom') || 15,
                    bg: $this.data('map-bg'),
                    scrollwheel: !!$this.data('map-scrollwheel'),
                    draggable: !!$this.data('map-draggable'),
                    styles: $this.data('map-styles'),
                    icon: $this.data('map-icon'),
                    controls: ($this.data('map-controls') || '').split(',')
                }, options);

                //..

                init(data);

                $this.data(dataName, data);
            } else {
                $.extend(data, options);
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

    $(function () {
        $('[data-' + name + ']')[fnName]();
    });
});