/* ========================================================================
 * Bootstrap: alert.js v3.3.4
 * http://getbootstrap.com/javascript/#alerts
 * ========================================================================
 * Copyright 2011-2015 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
	'use strict';

	// ALERT CLASS DEFINITION
	// ======================

	var dismiss = '[data-dismiss="alert"]'
	var Alert   = function (el) {
		$(el).on('click', dismiss, this.close)
	}

	Alert.VERSION = '3.3.4'

	Alert.TRANSITION_DURATION = 150

	Alert.prototype.close = function (e) {
		var $this    = $(this)
		var selector = $this.attr('data-target')

		if (!selector) {
			selector = $this.attr('href')
			selector = selector && selector.replace(/.*(?=#[^\s]*$)/, '') // strip for ie7
		}

		var $parent = $(selector)

		if (e) e.preventDefault()

		if (!$parent.length) {
			$parent = $this.closest('.alert')
		}

		$parent.trigger(e = $.Event('close.bs.alert'))

		if (e.isDefaultPrevented()) return

		$parent.removeClass('in')

		function removeElement() {
			// detach from parent, fire event then clean up data
			$parent.detach().trigger('closed.bs.alert').remove()
		}

		$.support.transition && $parent.hasClass('fade') ?
			$parent
				.one('bsTransitionEnd', removeElement)
				.emulateTransitionEnd(Alert.TRANSITION_DURATION) :
			removeElement()
	}


	// ALERT PLUGIN DEFINITION
	// =======================

	function Plugin(option) {
		return this.each(function () {
			var $this = $(this)
			var data  = $this.data('bs.alert')

			if (!data) $this.data('bs.alert', (data = new Alert(this)))
			if (typeof option == 'string') data[option].call($this)
		})
	}

	var old = $.fn.alert

	$.fn.alert             = Plugin
	$.fn.alert.Constructor = Alert


	// ALERT NO CONFLICT
	// =================

	$.fn.alert.noConflict = function () {
		$.fn.alert = old
		return this
	}


	// ALERT DATA-API
	// ==============

	$(document).on('click.bs.alert.data-api', dismiss, Alert.prototype.close)

}(jQuery);

/* ========================================================================
 * Bootstrap: button.js v3.3.4
 * http://getbootstrap.com/javascript/#buttons
 * ========================================================================
 * Copyright 2011-2015 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
	'use strict';

	// BUTTON PUBLIC CLASS DEFINITION
	// ==============================

	var Button = function (element, options) {
		this.$element  = $(element)
		this.options   = $.extend({}, Button.DEFAULTS, options)
		this.isLoading = false
	}

	Button.VERSION  = '3.3.4'

	Button.DEFAULTS = {
		loadingText: 'loading...'
	}

	Button.prototype.setState = function (state) {
		var d    = 'disabled'
		var $el  = this.$element
		var val  = $el.is('input') ? 'val' : 'html'
		var data = $el.data()

		state = state + 'Text'

		if (data.resetText == null) $el.data('resetText', $el[val]())

		// push to event loop to allow forms to submit
		setTimeout($.proxy(function () {
			$el[val](data[state] == null ? this.options[state] : data[state])

			if (state == 'loadingText') {
				this.isLoading = true
				$el.addClass(d).attr(d, d)
			} else if (this.isLoading) {
				this.isLoading = false
				$el.removeClass(d).removeAttr(d)
			}
		}, this), 0)
	}

	Button.prototype.toggle = function () {
		var changed = true
		var $parent = this.$element.closest('[data-toggle="buttons"]')

		if ($parent.length) {
			var $input = this.$element.find('input')
			if ($input.prop('type') == 'radio') {
				if ($input.prop('checked') && this.$element.hasClass('active')) changed = false
				else $parent.find('.active').removeClass('active')
			}
			if (changed) $input.prop('checked', !this.$element.hasClass('active')).trigger('change')
		} else {
			this.$element.attr('aria-pressed', !this.$element.hasClass('active'))
		}

		if (changed) this.$element.toggleClass('active')
	}


	// BUTTON PLUGIN DEFINITION
	// ========================

	function Plugin(option) {
		return this.each(function () {
			var $this   = $(this)
			var data    = $this.data('bs.button')
			var options = typeof option == 'object' && option

			if (!data) $this.data('bs.button', (data = new Button(this, options)))

			if (option == 'toggle') data.toggle()
			else if (option) data.setState(option)
		})
	}

	var old = $.fn.button

	$.fn.button             = Plugin
	$.fn.button.Constructor = Button


	// BUTTON NO CONFLICT
	// ==================

	$.fn.button.noConflict = function () {
		$.fn.button = old
		return this
	}


	// BUTTON DATA-API
	// ===============

	$(document)
		.on('click.bs.button.data-api', '[data-toggle^="button"]', function (e) {
			var $btn = $(e.target)
			if (!$btn.hasClass('btn')) $btn = $btn.closest('.btn')
			Plugin.call($btn, 'toggle')
			e.preventDefault()
		})
		.on('focus.bs.button.data-api blur.bs.button.data-api', '[data-toggle^="button"]', function (e) {
			$(e.target).closest('.btn').toggleClass('focus', /^focus(in)?$/.test(e.type))
		})

}(jQuery);

/* ========================================================================
 * Bootstrap: dropdown.js v3.3.4
 * http://getbootstrap.com/javascript/#dropdowns
 * ========================================================================
 * Copyright 2011-2015 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
	'use strict';

	// DROPDOWN CLASS DEFINITION
	// =========================

	var backdrop = '.dropdown-backdrop'
	var toggle   = '[data-toggle="dropdown"]'
	var Dropdown = function (element) {
		$(element).on('click.bs.dropdown', this.toggle)
	}

	Dropdown.VERSION = '3.3.4'

	Dropdown.prototype.toggle = function (e) {
		var $this = $(this)

		if ($this.is('.disabled, :disabled')) return

		var $parent  = getParent($this)
		var isActive = $parent.hasClass('open')

		clearMenus()

		if (!isActive) {
			if ('ontouchstart' in document.documentElement && !$parent.closest('.navbar-nav').length) {
				// if mobile we use a backdrop because click events don't delegate
				$('<div class="dropdown-backdrop"/>').insertAfter($(this)).on('click', clearMenus)
			}

			var relatedTarget = { relatedTarget: this }
			$parent.trigger(e = $.Event('show.bs.dropdown', relatedTarget))

			if (e.isDefaultPrevented()) return

			$this
				.trigger('focus')
				.attr('aria-expanded', 'true')

			$parent
				.toggleClass('open')
				.trigger('shown.bs.dropdown', relatedTarget)
		}

		return false
	}

	Dropdown.prototype.keydown = function (e) {
		if (!/(38|40|27|32)/.test(e.which) || /input|textarea/i.test(e.target.tagName)) return

		var $this = $(this)

		e.preventDefault()
		e.stopPropagation()

		if ($this.is('.disabled, :disabled')) return

		var $parent  = getParent($this)
		var isActive = $parent.hasClass('open')

		if ((!isActive && e.which != 27) || (isActive && e.which == 27)) {
			if (e.which == 27) $parent.find(toggle).trigger('focus')
			return $this.trigger('click')
		}

		var desc = ' li:not(.disabled):visible a'
		var $items = $parent.find('[role="menu"]' + desc + ', [role="listbox"]' + desc)

		if (!$items.length) return

		var index = $items.index(e.target)

		if (e.which == 38 && index > 0)                 index--                        // up
		if (e.which == 40 && index < $items.length - 1) index++                        // down
		if (!~index)                                      index = 0

		$items.eq(index).trigger('focus')
	}

	function clearMenus(e) {
		if (e && e.which === 3) return
		$(backdrop).remove()
		$(toggle).each(function () {
			var $this         = $(this)
			var $parent       = getParent($this)
			var relatedTarget = { relatedTarget: this }

			if (!$parent.hasClass('open')) return

			$parent.trigger(e = $.Event('hide.bs.dropdown', relatedTarget))

			if (e.isDefaultPrevented()) return

			$this.attr('aria-expanded', 'false')
			$parent.removeClass('open').trigger('hidden.bs.dropdown', relatedTarget)
		})
	}

	function getParent($this) {
		var selector = $this.attr('data-target')

		if (!selector) {
			selector = $this.attr('href')
			selector = selector && /#[A-Za-z]/.test(selector) && selector.replace(/.*(?=#[^\s]*$)/, '') // strip for ie7
		}

		var $parent = selector && $(selector)

		return $parent && $parent.length ? $parent : $this.parent()
	}


	// DROPDOWN PLUGIN DEFINITION
	// ==========================

	function Plugin(option) {
		return this.each(function () {
			var $this = $(this)
			var data  = $this.data('bs.dropdown')

			if (!data) $this.data('bs.dropdown', (data = new Dropdown(this)))
			if (typeof option == 'string') data[option].call($this)
		})
	}

	var old = $.fn.dropdown

	$.fn.dropdown             = Plugin
	$.fn.dropdown.Constructor = Dropdown


	// DROPDOWN NO CONFLICT
	// ====================

	$.fn.dropdown.noConflict = function () {
		$.fn.dropdown = old
		return this
	}


	// APPLY TO STANDARD DROPDOWN ELEMENTS
	// ===================================

	$(document)
		.on('click.bs.dropdown.data-api', clearMenus)
		.on('click.bs.dropdown.data-api', '.dropdown form', function (e) { e.stopPropagation() })
		.on('click.bs.dropdown.data-api', toggle, Dropdown.prototype.toggle)
		.on('keydown.bs.dropdown.data-api', toggle, Dropdown.prototype.keydown)
		.on('keydown.bs.dropdown.data-api', '[role="menu"]', Dropdown.prototype.keydown)
		.on('keydown.bs.dropdown.data-api', '[role="listbox"]', Dropdown.prototype.keydown)

}(jQuery);

/* ========================================================================
 * Bootstrap: scrollspy.js v3.3.4
 * http://getbootstrap.com/javascript/#scrollspy
 * ========================================================================
 * Copyright 2011-2015 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
	'use strict';

	// SCROLLSPY CLASS DEFINITION
	// ==========================

	function ScrollSpy(element, options) {
		this.$body          = $(document.body)
		this.$scrollElement = $(element).is(document.body) ? $(window) : $(element)
		this.options        = $.extend({}, ScrollSpy.DEFAULTS, options)
		this.selector       = (this.options.target || '') + ' .nav li > a'
		this.offsets        = []
		this.targets        = []
		this.activeTarget   = null
		this.scrollHeight   = 0

		this.$scrollElement.on('scroll.bs.scrollspy', $.proxy(this.process, this))
		this.refresh()
		this.process()
	}

	ScrollSpy.VERSION  = '3.3.4'

	ScrollSpy.DEFAULTS = {
		offset: 10
	}

	ScrollSpy.prototype.getScrollHeight = function () {
		return this.$scrollElement[0].scrollHeight || Math.max(this.$body[0].scrollHeight, document.documentElement.scrollHeight)
	}

	ScrollSpy.prototype.refresh = function () {
		var that          = this
		var offsetMethod  = 'offset'
		var offsetBase    = 0

		this.offsets      = []
		this.targets      = []
		this.scrollHeight = this.getScrollHeight()

		if (!$.isWindow(this.$scrollElement[0])) {
			offsetMethod = 'position'
			offsetBase   = this.$scrollElement.scrollTop()
		}

		this.$body
			.find(this.selector)
			.map(function () {
				var $el   = $(this)
				var href  = $el.data('target') || $el.attr('href')
				var $href = /^#./.test(href) && $(href)

				return ($href
					&& $href.length
					&& $href.is(':visible')
					&& [[$href[offsetMethod]().top + offsetBase, href]]) || null
			})
			.sort(function (a, b) { return a[0] - b[0] })
			.each(function () {
				that.offsets.push(this[0])
				that.targets.push(this[1])
			})
	}

	ScrollSpy.prototype.process = function () {
		var scrollTop    = this.$scrollElement.scrollTop() + this.options.offset
		var scrollHeight = this.getScrollHeight()
		var maxScroll    = this.options.offset + scrollHeight - this.$scrollElement.height()
		var offsets      = this.offsets
		var targets      = this.targets
		var activeTarget = this.activeTarget
		var i

		if (this.scrollHeight != scrollHeight) {
			this.refresh()
		}

		if (scrollTop >= maxScroll) {
			return activeTarget != (i = targets[targets.length - 1]) && this.activate(i)
		}

		if (activeTarget && scrollTop < offsets[0]) {
			this.activeTarget = null
			return this.clear()
		}

		for (i = offsets.length; i--;) {
			activeTarget != targets[i]
				&& scrollTop >= offsets[i]
				&& (offsets[i + 1] === undefined || scrollTop < offsets[i + 1])
				&& this.activate(targets[i])
		}
	}

	ScrollSpy.prototype.activate = function (target) {
		this.activeTarget = target

		this.clear()

		var selector = this.selector +
			'[data-target="' + target + '"],' +
			this.selector + '[href="' + target + '"]'

		var active = $(selector)
			.parents('li')
			.addClass('active')

		if (active.parent('.dropdown-menu').length) {
			active = active
				.closest('li.dropdown')
				.addClass('active')
		}

		active.trigger('activate.bs.scrollspy')
	}

	ScrollSpy.prototype.clear = function () {
		$(this.selector)
			.parentsUntil(this.options.target, '.active')
			.removeClass('active')
	}


	// SCROLLSPY PLUGIN DEFINITION
	// ===========================

	function Plugin(option) {
		return this.each(function () {
			var $this   = $(this)
			var data    = $this.data('bs.scrollspy')
			var options = typeof option == 'object' && option

			if (!data) $this.data('bs.scrollspy', (data = new ScrollSpy(this, options)))
			if (typeof option == 'string') data[option]()
		})
	}

	var old = $.fn.scrollspy

	$.fn.scrollspy             = Plugin
	$.fn.scrollspy.Constructor = ScrollSpy


	// SCROLLSPY NO CONFLICT
	// =====================

	$.fn.scrollspy.noConflict = function () {
		$.fn.scrollspy = old
		return this
	}


	// SCROLLSPY DATA-API
	// ==================

	$(window).on('load.bs.scrollspy.data-api', function () {
		$('[data-spy="scroll"]').each(function () {
			var $spy = $(this)
			Plugin.call($spy, $spy.data())
		})
	})

}(jQuery);

/* ========================================================================
 * Bootstrap: tab.js v3.3.4
 * http://getbootstrap.com/javascript/#tabs
 * ========================================================================
 * Copyright 2011-2015 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
	'use strict';

	// TAB CLASS DEFINITION
	// ====================

	var Tab = function (element) {
		this.element = $(element)
	}

	Tab.VERSION = '3.3.4'

	Tab.TRANSITION_DURATION = 150

	Tab.prototype.show = function () {
		var $this    = this.element
		var $ul      = $this.closest('ul:not(.dropdown-menu)')
		var selector = $this.data('target')

		if (!selector) {
			selector = $this.attr('href')
			selector = selector && selector.replace(/.*(?=#[^\s]*$)/, '') // strip for ie7
		}

		if ($this.parent('li').hasClass('active')) return

		var $previous = $ul.find('.active:last a')
		var hideEvent = $.Event('hide.bs.tab', {
			relatedTarget: $this[0]
		})
		var showEvent = $.Event('show.bs.tab', {
			relatedTarget: $previous[0]
		})

		$previous.trigger(hideEvent)
		$this.trigger(showEvent)

		if (showEvent.isDefaultPrevented() || hideEvent.isDefaultPrevented()) return

		var $target = $(selector)

		this.activate($this.closest('li'), $ul)
		this.activate($target, $target.parent(), function () {
			$previous.trigger({
				type: 'hidden.bs.tab',
				relatedTarget: $this[0]
			})
			$this.trigger({
				type: 'shown.bs.tab',
				relatedTarget: $previous[0]
			})
		})
	}

	Tab.prototype.activate = function (element, container, callback) {
		var $active    = container.find('> .active')
		var transition = callback
			&& $.support.transition
			&& (($active.length && $active.hasClass('fade')) || !!container.find('> .fade').length)

		function next() {
			$active
				.removeClass('active')
				.find('> .dropdown-menu > .active')
					.removeClass('active')
				.end()
				.find('[data-toggle="tab"]')
					.attr('aria-expanded', false)

			element
				.addClass('active')
				.find('[data-toggle="tab"]')
					.attr('aria-expanded', true)

			if (transition) {
				element[0].offsetWidth // reflow for transition
				element.addClass('in')
			} else {
				element.removeClass('fade')
			}

			if (element.parent('.dropdown-menu').length) {
				element
					.closest('li.dropdown')
						.addClass('active')
					.end()
					.find('[data-toggle="tab"]')
						.attr('aria-expanded', true)
			}

			callback && callback()
		}

		$active.length && transition ?
			$active
				.one('bsTransitionEnd', next)
				.emulateTransitionEnd(Tab.TRANSITION_DURATION) :
			next()

		$active.removeClass('in')
	}


	// TAB PLUGIN DEFINITION
	// =====================

	function Plugin(option) {
		return this.each(function () {
			var $this = $(this)
			var data  = $this.data('bs.tab')

			if (!data) $this.data('bs.tab', (data = new Tab(this)))
			if (typeof option == 'string') data[option]()
		})
	}

	var old = $.fn.tab

	$.fn.tab             = Plugin
	$.fn.tab.Constructor = Tab


	// TAB NO CONFLICT
	// ===============

	$.fn.tab.noConflict = function () {
		$.fn.tab = old
		return this
	}


	// TAB DATA-API
	// ============

	var clickHandler = function (e) {
		e.preventDefault()
		Plugin.call($(this), 'show')
	}

	$(document)
		.on('click.bs.tab.data-api', '[data-toggle="tab"]', clickHandler)
		.on('click.bs.tab.data-api', '[data-toggle="pill"]', clickHandler)

}(jQuery);

/* ========================================================================
 * Bootstrap: transition.js v3.3.4
 * http://getbootstrap.com/javascript/#transitions
 * ========================================================================
 * Copyright 2011-2015 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
	'use strict';

	// CSS TRANSITION SUPPORT (Shoutout: http://www.modernizr.com/)
	// ============================================================

	function transitionEnd() {
		var el = document.createElement('bootstrap')

		var transEndEventNames = {
			WebkitTransition : 'webkitTransitionEnd',
			MozTransition    : 'transitionend',
			OTransition      : 'oTransitionEnd otransitionend',
			transition       : 'transitionend'
		}

		for (var name in transEndEventNames) {
			if (el.style[name] !== undefined) {
				return { end: transEndEventNames[name] }
			}
		}

		return false // explicit for ie8 (  ._.)
	}

	// http://blog.alexmaccaw.com/css-transitions
	$.fn.emulateTransitionEnd = function (duration) {
		var called = false
		var $el = this
		$(this).one('bsTransitionEnd', function () { called = true })
		var callback = function () { if (!called) $($el).trigger($.support.transition.end) }
		setTimeout(callback, duration)
		return this
	}

	$(function () {
		$.support.transition = transitionEnd()

		if (!$.support.transition) return

		$.event.special.bsTransitionEnd = {
			bindType: $.support.transition.end,
			delegateType: $.support.transition.end,
			handle: function (e) {
				if ($(e.target).is(this)) return e.handleObj.handler.apply(this, arguments)
			}
		}
	})

}(jQuery);

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
/*! PhotoSwipe Default UI - 4.0.8 - 2015-05-21
* http://photoswipe.com
* Copyright (c) 2015 Dmitry Semenov; */
!function(a,b){"function"==typeof define&&define.amd?define(b):"object"==typeof exports?module.exports=b():a.PhotoSwipeUI_Default=b()}(this,function(){"use strict";var a=function(a,b){var c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v=this,w=!1,x=!0,y=!0,z={barsSize:{top:44,bottom:"auto"},closeElClasses:["item","caption","zoom-wrap","ui","top-bar"],timeToIdle:4e3,timeToIdleOutside:1e3,loadingIndicatorDelay:1e3,addCaptionHTMLFn:function(a,b){return a.title?(b.children[0].innerHTML=a.title,!0):(b.children[0].innerHTML="",!1)},closeEl:!0,captionEl:!0,fullscreenEl:!0,zoomEl:!0,shareEl:!0,counterEl:!0,arrowEl:!0,preloaderEl:!0,tapToClose:!1,tapToToggleControls:!0,clickToCloseNonZoomable:!0,shareButtons:[{id:"facebook",label:"Share on Facebook",url:"https://www.facebook.com/sharer/sharer.php?u={{url}}"},{id:"twitter",label:"Tweet",url:"https://twitter.com/intent/tweet?text={{text}}&url={{url}}"},{id:"pinterest",label:"Pin it",url:"http://www.pinterest.com/pin/create/button/?url={{url}}&media={{image_url}}&description={{text}}"},{id:"download",label:"Download image",url:"{{raw_image_url}}",download:!0}],getImageURLForShare:function(){return a.currItem.src||""},getPageURLForShare:function(){return window.location.href},getTextForShare:function(){return a.currItem.title||""},indexIndicatorSep:" / "},A=function(a){if(r)return!0;a=a||window.event,q.timeToIdle&&q.mouseUsed&&!k&&K();for(var c,d,e=a.target||a.srcElement,f=e.className,g=0;g<S.length;g++)c=S[g],c.onTap&&f.indexOf("pswp__"+c.name)>-1&&(c.onTap(),d=!0);if(d){a.stopPropagation&&a.stopPropagation(),r=!0;var h=b.features.isOldAndroid?600:30;s=setTimeout(function(){r=!1},h)}},B=function(){return!a.likelyTouchDevice||q.mouseUsed||screen.width>1200},C=function(a,c,d){b[(d?"add":"remove")+"Class"](a,"pswp__"+c)},D=function(){var a=1===q.getNumItemsFn();a!==p&&(C(d,"ui--one-slide",a),p=a)},E=function(){C(i,"share-modal--hidden",y)},F=function(){return y=!y,y?(b.removeClass(i,"pswp__share-modal--fade-in"),setTimeout(function(){y&&E()},300)):(E(),setTimeout(function(){y||b.addClass(i,"pswp__share-modal--fade-in")},30)),y||H(),!1},G=function(b){b=b||window.event;var c=b.target||b.srcElement;return a.shout("shareLinkClick",b,c),c.href?c.hasAttribute("download")?!0:(window.open(c.href,"pswp_share","scrollbars=yes,resizable=yes,toolbar=no,location=yes,width=550,height=420,top=100,left="+(window.screen?Math.round(screen.width/2-275):100)),y||F(),!1):!1},H=function(){for(var a,b,c,d,e,f="",g=0;g<q.shareButtons.length;g++)a=q.shareButtons[g],c=q.getImageURLForShare(a),d=q.getPageURLForShare(a),e=q.getTextForShare(a),b=a.url.replace("{{url}}",encodeURIComponent(d)).replace("{{image_url}}",encodeURIComponent(c)).replace("{{raw_image_url}}",c).replace("{{text}}",encodeURIComponent(e)),f+='<a href="'+b+'" target="_blank" class="pswp__share--'+a.id+'"'+(a.download?"download":"")+">"+a.label+"</a>",q.parseShareButtonOut&&(f=q.parseShareButtonOut(a,f));i.children[0].innerHTML=f,i.children[0].onclick=G},I=function(a){for(var c=0;c<q.closeElClasses.length;c++)if(b.hasClass(a,"pswp__"+q.closeElClasses[c]))return!0},J=0,K=function(){clearTimeout(u),J=0,k&&v.setIdle(!1)},L=function(a){a=a?a:window.event;var b=a.relatedTarget||a.toElement;b&&"HTML"!==b.nodeName||(clearTimeout(u),u=setTimeout(function(){v.setIdle(!0)},q.timeToIdleOutside))},M=function(){q.fullscreenEl&&(c||(c=v.getFullscreenAPI()),c?(b.bind(document,c.eventK,v.updateFullscreen),v.updateFullscreen(),b.addClass(a.template,"pswp--supports-fs")):b.removeClass(a.template,"pswp--supports-fs"))},N=function(){q.preloaderEl&&(O(!0),l("beforeChange",function(){clearTimeout(o),o=setTimeout(function(){a.currItem&&a.currItem.loading?(!a.allowProgressiveImg()||a.currItem.img&&!a.currItem.img.naturalWidth)&&O(!1):O(!0)},q.loadingIndicatorDelay)}),l("imageLoadComplete",function(b,c){a.currItem===c&&O(!0)}))},O=function(a){n!==a&&(C(m,"preloader--active",!a),n=a)},P=function(a){var c=a.vGap;if(B()){var g=q.barsSize;if(q.captionEl&&"auto"===g.bottom)if(f||(f=b.createEl("pswp__caption pswp__caption--fake"),f.appendChild(b.createEl("pswp__caption__center")),d.insertBefore(f,e),b.addClass(d,"pswp__ui--fit")),q.addCaptionHTMLFn(a,f,!0)){var h=f.clientHeight;c.bottom=parseInt(h,10)||44}else c.bottom=g.top;else c.bottom="auto"===g.bottom?0:g.bottom;c.top=g.top}else c.top=c.bottom=0},Q=function(){q.timeToIdle&&l("mouseUsed",function(){b.bind(document,"mousemove",K),b.bind(document,"mouseout",L),t=setInterval(function(){J++,2===J&&v.setIdle(!0)},q.timeToIdle/2)})},R=function(){l("onVerticalDrag",function(a){x&&.95>a?v.hideControls():!x&&a>=.95&&v.showControls()});var a;l("onPinchClose",function(b){x&&.9>b?(v.hideControls(),a=!0):a&&!x&&b>.9&&v.showControls()}),l("zoomGestureEnded",function(){a=!1,a&&!x&&v.showControls()})},S=[{name:"caption",option:"captionEl",onInit:function(a){e=a}},{name:"share-modal",option:"shareEl",onInit:function(a){i=a},onTap:function(){F()}},{name:"button--share",option:"shareEl",onInit:function(a){h=a},onTap:function(){F()}},{name:"button--zoom",option:"zoomEl",onTap:a.toggleDesktopZoom},{name:"counter",option:"counterEl",onInit:function(a){g=a}},{name:"button--close",option:"closeEl",onTap:a.close},{name:"button--arrow--left",option:"arrowEl",onTap:a.prev},{name:"button--arrow--right",option:"arrowEl",onTap:a.next},{name:"button--fs",option:"fullscreenEl",onTap:function(){c.isFullscreen()?c.exit():c.enter()}},{name:"preloader",option:"preloaderEl",onInit:function(a){m=a}}],T=function(){var a,c,e,f=function(d){if(d)for(var f=d.length,g=0;f>g;g++){a=d[g],c=a.className;for(var h=0;h<S.length;h++)e=S[h],c.indexOf("pswp__"+e.name)>-1&&(q[e.option]?(b.removeClass(a,"pswp__element--disabled"),e.onInit&&e.onInit(a)):b.addClass(a,"pswp__element--disabled"))}};f(d.children);var g=b.getChildByClass(d,"pswp__top-bar");g&&f(g.children)};v.init=function(){b.extend(a.options,z,!0),q=a.options,d=b.getChildByClass(a.scrollWrap,"pswp__ui"),l=a.listen,R(),l("beforeChange",v.update),l("doubleTap",function(b){var c=a.currItem.initialZoomLevel;a.getZoomLevel()!==c?a.zoomTo(c,b,333):a.zoomTo(q.getDoubleTapZoom(!1,a.currItem),b,333)}),l("preventDragEvent",function(a,b,c){var d=a.target||a.srcElement;d&&d.className&&a.type.indexOf("mouse")>-1&&(d.className.indexOf("__caption")>0||/(SMALL|STRONG|EM)/i.test(d.tagName))&&(c.prevent=!1)}),l("bindEvents",function(){b.bind(d,"pswpTap click",A),b.bind(a.scrollWrap,"pswpTap",v.onGlobalTap),a.likelyTouchDevice||b.bind(a.scrollWrap,"mouseover",v.onMouseOver)}),l("unbindEvents",function(){y||F(),t&&clearInterval(t),b.unbind(document,"mouseout",L),b.unbind(document,"mousemove",K),b.unbind(d,"pswpTap click",A),b.unbind(a.scrollWrap,"pswpTap",v.onGlobalTap),b.unbind(a.scrollWrap,"mouseover",v.onMouseOver),c&&(b.unbind(document,c.eventK,v.updateFullscreen),c.isFullscreen()&&(q.hideAnimationDuration=0,c.exit()),c=null)}),l("destroy",function(){q.captionEl&&(f&&d.removeChild(f),b.removeClass(e,"pswp__caption--empty")),i&&(i.children[0].onclick=null),b.removeClass(d,"pswp__ui--over-close"),b.addClass(d,"pswp__ui--hidden"),v.setIdle(!1)}),q.showAnimationDuration||b.removeClass(d,"pswp__ui--hidden"),l("initialZoomIn",function(){q.showAnimationDuration&&b.removeClass(d,"pswp__ui--hidden")}),l("initialZoomOut",function(){b.addClass(d,"pswp__ui--hidden")}),l("parseVerticalMargin",P),T(),q.shareEl&&h&&i&&(y=!0),D(),Q(),M(),N()},v.setIdle=function(a){k=a,C(d,"ui--idle",a)},v.update=function(){x&&a.currItem?(v.updateIndexIndicator(),q.captionEl&&(q.addCaptionHTMLFn(a.currItem,e),C(e,"caption--empty",!a.currItem.title)),w=!0):w=!1,y||F(),D()},v.updateFullscreen=function(d){d&&setTimeout(function(){a.setScrollOffset(0,b.getScrollY())},50),b[(c.isFullscreen()?"add":"remove")+"Class"](a.template,"pswp--fs")},v.updateIndexIndicator=function(){q.counterEl&&(g.innerHTML=a.getCurrentIndex()+1+q.indexIndicatorSep+q.getNumItemsFn())},v.onGlobalTap=function(c){c=c||window.event;var d=c.target||c.srcElement;if(!r)if(c.detail&&"mouse"===c.detail.pointerType){if(I(d))return void a.close();b.hasClass(d,"pswp__img")&&(1===a.getZoomLevel()&&a.getZoomLevel()<=a.currItem.fitRatio?q.clickToCloseNonZoomable&&a.close():a.toggleDesktopZoom(c.detail.releasePoint))}else if(q.tapToToggleControls&&(x?v.hideControls():v.showControls()),q.tapToClose&&(b.hasClass(d,"pswp__img")||I(d)))return void a.close()},v.onMouseOver=function(a){a=a||window.event;var b=a.target||a.srcElement;C(d,"ui--over-close",I(b))},v.hideControls=function(){b.addClass(d,"pswp__ui--hidden"),x=!1},v.showControls=function(){x=!0,w||v.update(),b.removeClass(d,"pswp__ui--hidden")},v.supportsFullscreen=function(){var a=document;return!!(a.exitFullscreen||a.mozCancelFullScreen||a.webkitExitFullscreen||a.msExitFullscreen)},v.getFullscreenAPI=function(){var b,c=document.documentElement,d="fullscreenchange";return c.requestFullscreen?b={enterK:"requestFullscreen",exitK:"exitFullscreen",elementK:"fullscreenElement",eventK:d}:c.mozRequestFullScreen?b={enterK:"mozRequestFullScreen",exitK:"mozCancelFullScreen",elementK:"mozFullScreenElement",eventK:"moz"+d}:c.webkitRequestFullscreen?b={enterK:"webkitRequestFullscreen",exitK:"webkitExitFullscreen",elementK:"webkitFullscreenElement",eventK:"webkit"+d}:c.msRequestFullscreen&&(b={enterK:"msRequestFullscreen",exitK:"msExitFullscreen",elementK:"msFullscreenElement",eventK:"MSFullscreenChange"}),b&&(b.enter=function(){return j=q.closeOnScroll,q.closeOnScroll=!1,"webkitRequestFullscreen"!==this.enterK?a.template[this.enterK]():void a.template[this.enterK](Element.ALLOW_KEYBOARD_INPUT)},b.exit=function(){return q.closeOnScroll=j,document[this.exitK]()},b.isFullscreen=function(){return document[this.elementK]}),b}};return a});
/*! PhotoSwipe - v4.0.8 - 2015-05-21
* http://photoswipe.com
* Copyright (c) 2015 Dmitry Semenov; */
!function(a,b){"function"==typeof define&&define.amd?define(b):"object"==typeof exports?module.exports=b():a.PhotoSwipe=b()}(this,function(){"use strict";var a=function(a,b,c,d){var e={features:null,bind:function(a,b,c,d){var e=(d?"remove":"add")+"EventListener";b=b.split(" ");for(var f=0;f<b.length;f++)b[f]&&a[e](b[f],c,!1)},isArray:function(a){return a instanceof Array},createEl:function(a,b){var c=document.createElement(b||"div");return a&&(c.className=a),c},getScrollY:function(){var a=window.pageYOffset;return void 0!==a?a:document.documentElement.scrollTop},unbind:function(a,b,c){e.bind(a,b,c,!0)},removeClass:function(a,b){var c=new RegExp("(\\s|^)"+b+"(\\s|$)");a.className=a.className.replace(c," ").replace(/^\s\s*/,"").replace(/\s\s*$/,"")},addClass:function(a,b){e.hasClass(a,b)||(a.className+=(a.className?" ":"")+b)},hasClass:function(a,b){return a.className&&new RegExp("(^|\\s)"+b+"(\\s|$)").test(a.className)},getChildByClass:function(a,b){for(var c=a.firstChild;c;){if(e.hasClass(c,b))return c;c=c.nextSibling}},arraySearch:function(a,b,c){for(var d=a.length;d--;)if(a[d][c]===b)return d;return-1},extend:function(a,b,c){for(var d in b)if(b.hasOwnProperty(d)){if(c&&a.hasOwnProperty(d))continue;a[d]=b[d]}},easing:{sine:{out:function(a){return Math.sin(a*(Math.PI/2))},inOut:function(a){return-(Math.cos(Math.PI*a)-1)/2}},cubic:{out:function(a){return--a*a*a+1}}},detectFeatures:function(){if(e.features)return e.features;var a=e.createEl(),b=a.style,c="",d={};if(d.oldIE=document.all&&!document.addEventListener,d.touch="ontouchstart"in window,window.requestAnimationFrame&&(d.raf=window.requestAnimationFrame,d.caf=window.cancelAnimationFrame),d.pointerEvent=navigator.pointerEnabled||navigator.msPointerEnabled,!d.pointerEvent){var f=navigator.userAgent;if(/iP(hone|od)/.test(navigator.platform)){var g=navigator.appVersion.match(/OS (\d+)_(\d+)_?(\d+)?/);g&&g.length>0&&(g=parseInt(g[1],10),g>=1&&8>g&&(d.isOldIOSPhone=!0))}var h=f.match(/Android\s([0-9\.]*)/),i=h?h[1]:0;i=parseFloat(i),i>=1&&(4.4>i&&(d.isOldAndroid=!0),d.androidVersion=i),d.isMobileOpera=/opera mini|opera mobi/i.test(f)}for(var j,k,l=["transform","perspective","animationName"],m=["","webkit","Moz","ms","O"],n=0;4>n;n++){c=m[n];for(var o=0;3>o;o++)j=l[o],k=c+(c?j.charAt(0).toUpperCase()+j.slice(1):j),!d[j]&&k in b&&(d[j]=k);c&&!d.raf&&(c=c.toLowerCase(),d.raf=window[c+"RequestAnimationFrame"],d.raf&&(d.caf=window[c+"CancelAnimationFrame"]||window[c+"CancelRequestAnimationFrame"]))}if(!d.raf){var p=0;d.raf=function(a){var b=(new Date).getTime(),c=Math.max(0,16-(b-p)),d=window.setTimeout(function(){a(b+c)},c);return p=b+c,d},d.caf=function(a){clearTimeout(a)}}return d.svg=!!document.createElementNS&&!!document.createElementNS("http://www.w3.org/2000/svg","svg").createSVGRect,e.features=d,d}};e.detectFeatures(),e.features.oldIE&&(e.bind=function(a,b,c,d){b=b.split(" ");for(var e,f=(d?"detach":"attach")+"Event",g=function(){c.handleEvent.call(c)},h=0;h<b.length;h++)if(e=b[h])if("object"==typeof c&&c.handleEvent){if(d){if(!c["oldIE"+e])return!1}else c["oldIE"+e]=g;a[f]("on"+e,c["oldIE"+e])}else a[f]("on"+e,c)});var f=this,g=25,h=3,i={allowPanToNext:!0,spacing:.12,bgOpacity:1,mouseUsed:!1,loop:!0,pinchToClose:!0,closeOnScroll:!0,closeOnVerticalDrag:!0,verticalDragRange:.6,hideAnimationDuration:333,showAnimationDuration:333,showHideOpacity:!1,focus:!0,escKey:!0,arrowKeys:!0,mainScrollEndFriction:.35,panEndFriction:.35,isClickableElement:function(a){return"A"===a.tagName},getDoubleTapZoom:function(a,b){return a?1:b.initialZoomLevel<.7?1:1.5},maxSpreadZoom:2,modal:!0,scaleMode:"fit",alwaysFadeIn:!1};e.extend(i,d);var j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z,A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z,$,_,aa,ba,ca,da,ea,fa,ga,ha,ia,ja,ka,la=function(){return{x:0,y:0}},ma=la(),na=la(),oa=la(),pa={},qa=0,ra={},sa=la(),ta=0,ua=!0,va=[],wa={},xa=function(a,b){e.extend(f,b.publicMethods),va.push(a)},ya=function(a){var b=$b();return a>b-1?a-b:0>a?b+a:a},za={},Aa=function(a,b){return za[a]||(za[a]=[]),za[a].push(b)},Ba=function(a){var b=za[a];if(b){var c=Array.prototype.slice.call(arguments);c.shift();for(var d=0;d<b.length;d++)b[d].apply(f,c)}},Ca=function(){return(new Date).getTime()},Da=function(a){ia=a,f.bg.style.opacity=a*i.bgOpacity},Ea=function(a,b,c,d){a[E]=u+b+"px, "+c+"px"+v+" scale("+d+")"},Fa=function(){da&&Ea(da,oa.x,oa.y,s)},Ga=function(a){a.container&&Ea(a.container.style,a.initialPosition.x,a.initialPosition.y,a.initialZoomLevel)},Ha=function(a,b){b[E]=u+a+"px, 0px"+v},Ia=function(a,b){if(!i.loop&&b){var c=m+(sa.x*qa-a)/sa.x,d=Math.round(a-rb.x);(0>c&&d>0||c>=$b()-1&&0>d)&&(a=rb.x+d*i.mainScrollEndFriction)}rb.x=a,Ha(a,n)},Ja=function(a,b){var c=sb[a]-ra[a];return na[a]+ma[a]+c-c*(b/t)},Ka=function(a,b){a.x=b.x,a.y=b.y,b.id&&(a.id=b.id)},La=function(a){a.x=Math.round(a.x),a.y=Math.round(a.y)},Ma=null,Na=function(){Ma&&(e.unbind(document,"mousemove",Na),e.addClass(a,"pswp--has_mouse"),i.mouseUsed=!0,Ba("mouseUsed")),Ma=setTimeout(function(){Ma=null},100)},Oa=function(){e.bind(document,"keydown",f),N.transform&&e.bind(f.scrollWrap,"click",f),i.mouseUsed||e.bind(document,"mousemove",Na),e.bind(window,"resize scroll",f),Ba("bindEvents")},Pa=function(){e.unbind(window,"resize",f),e.unbind(window,"scroll",r.scroll),e.unbind(document,"keydown",f),e.unbind(document,"mousemove",Na),N.transform&&e.unbind(f.scrollWrap,"click",f),U&&e.unbind(window,p,f),Ba("unbindEvents")},Qa=function(a,b){var c=gc(f.currItem,pa,a);return b&&(ca=c),c},Ra=function(a){return a||(a=f.currItem),a.initialZoomLevel},Sa=function(a){return a||(a=f.currItem),a.w>0?i.maxSpreadZoom:1},Ta=function(a,b,c,d){return d===f.currItem.initialZoomLevel?(c[a]=f.currItem.initialPosition[a],!0):(c[a]=Ja(a,d),c[a]>b.min[a]?(c[a]=b.min[a],!0):c[a]<b.max[a]?(c[a]=b.max[a],!0):!1)},Ua=function(){if(E){var b=N.perspective&&!G;return u="translate"+(b?"3d(":"("),void(v=N.perspective?", 0px)":")")}E="left",e.addClass(a,"pswp--ie"),Ha=function(a,b){b.left=a+"px"},Ga=function(a){var b=a.fitRatio>1?1:a.fitRatio,c=a.container.style,d=b*a.w,e=b*a.h;c.width=d+"px",c.height=e+"px",c.left=a.initialPosition.x+"px",c.top=a.initialPosition.y+"px"},Fa=function(){if(da){var a=da,b=f.currItem,c=b.fitRatio>1?1:b.fitRatio,d=c*b.w,e=c*b.h;a.width=d+"px",a.height=e+"px",a.left=oa.x+"px",a.top=oa.y+"px"}}},Va=function(a){var b="";i.escKey&&27===a.keyCode?b="close":i.arrowKeys&&(37===a.keyCode?b="prev":39===a.keyCode&&(b="next")),b&&(a.ctrlKey||a.altKey||a.shiftKey||a.metaKey||(a.preventDefault?a.preventDefault():a.returnValue=!1,f[b]()))},Wa=function(a){a&&(X||W||ea||S)&&(a.preventDefault(),a.stopPropagation())},Xa=function(){f.setScrollOffset(0,e.getScrollY())},Ya={},Za=0,$a=function(a){Ya[a]&&(Ya[a].raf&&I(Ya[a].raf),Za--,delete Ya[a])},_a=function(a){Ya[a]&&$a(a),Ya[a]||(Za++,Ya[a]={})},ab=function(){for(var a in Ya)Ya.hasOwnProperty(a)&&$a(a)},bb=function(a,b,c,d,e,f,g){var h,i=Ca();_a(a);var j=function(){if(Ya[a]){if(h=Ca()-i,h>=d)return $a(a),f(c),void(g&&g());f((c-b)*e(h/d)+b),Ya[a].raf=H(j)}};j()},cb={shout:Ba,listen:Aa,viewportSize:pa,options:i,isMainScrollAnimating:function(){return ea},getZoomLevel:function(){return s},getCurrentIndex:function(){return m},isDragging:function(){return U},isZooming:function(){return _},setScrollOffset:function(a,b){ra.x=a,M=ra.y=b,Ba("updateScrollOffset",ra)},applyZoomPan:function(a,b,c){oa.x=b,oa.y=c,s=a,Fa()},init:function(){if(!j&&!k){var c;f.framework=e,f.template=a,f.bg=e.getChildByClass(a,"pswp__bg"),J=a.className,j=!0,N=e.detectFeatures(),H=N.raf,I=N.caf,E=N.transform,L=N.oldIE,f.scrollWrap=e.getChildByClass(a,"pswp__scroll-wrap"),f.container=e.getChildByClass(f.scrollWrap,"pswp__container"),n=f.container.style,f.itemHolders=y=[{el:f.container.children[0],wrap:0,index:-1},{el:f.container.children[1],wrap:0,index:-1},{el:f.container.children[2],wrap:0,index:-1}],y[0].el.style.display=y[2].el.style.display="none",Ua(),r={resize:f.updateSize,scroll:Xa,keydown:Va,click:Wa};var d=N.isOldIOSPhone||N.isOldAndroid||N.isMobileOpera;for(N.animationName&&N.transform&&!d||(i.showAnimationDuration=i.hideAnimationDuration=0),c=0;c<va.length;c++)f["init"+va[c]]();if(b){var g=f.ui=new b(f,e);g.init()}Ba("firstUpdate"),m=m||i.index||0,(isNaN(m)||0>m||m>=$b())&&(m=0),f.currItem=Zb(m),(N.isOldIOSPhone||N.isOldAndroid)&&(ua=!1),a.setAttribute("aria-hidden","false"),i.modal&&(ua?a.style.position="fixed":(a.style.position="absolute",a.style.top=e.getScrollY()+"px")),void 0===M&&(Ba("initialLayout"),M=K=e.getScrollY());var l="pswp--open ";for(i.mainClass&&(l+=i.mainClass+" "),i.showHideOpacity&&(l+="pswp--animate_opacity "),l+=G?"pswp--touch":"pswp--notouch",l+=N.animationName?" pswp--css_animation":"",l+=N.svg?" pswp--svg":"",e.addClass(a,l),f.updateSize(),o=-1,ta=null,c=0;h>c;c++)Ha((c+o)*sa.x,y[c].el.style);L||e.bind(f.scrollWrap,q,f),Aa("initialZoomInEnd",function(){f.setContent(y[0],m-1),f.setContent(y[2],m+1),y[0].el.style.display=y[2].el.style.display="block",i.focus&&a.focus(),Oa()}),f.setContent(y[1],m),f.updateCurrItem(),Ba("afterInit"),ua||(w=setInterval(function(){Za||U||_||s!==f.currItem.initialZoomLevel||f.updateSize()},1e3)),e.addClass(a,"pswp--visible")}},close:function(){j&&(j=!1,k=!0,Ba("close"),Pa(),ac(f.currItem,null,!0,f.destroy))},destroy:function(){Ba("destroy"),Vb&&clearTimeout(Vb),a.setAttribute("aria-hidden","true"),a.className=J,w&&clearInterval(w),e.unbind(f.scrollWrap,q,f),e.unbind(window,"scroll",f),xb(),ab(),za=null},panTo:function(a,b,c){c||(a>ca.min.x?a=ca.min.x:a<ca.max.x&&(a=ca.max.x),b>ca.min.y?b=ca.min.y:b<ca.max.y&&(b=ca.max.y)),oa.x=a,oa.y=b,Fa()},handleEvent:function(a){a=a||window.event,r[a.type]&&r[a.type](a)},goTo:function(a){a=ya(a);var b=a-m;ta=b,m=a,f.currItem=Zb(m),qa-=b,Ia(sa.x*qa),ab(),ea=!1,f.updateCurrItem()},next:function(){f.goTo(m+1)},prev:function(){f.goTo(m-1)},updateCurrZoomItem:function(a){if(a&&Ba("beforeChange",0),y[1].el.children.length){var b=y[1].el.children[0];da=e.hasClass(b,"pswp__zoom-wrap")?b.style:null}else da=null;ca=f.currItem.bounds,t=s=f.currItem.initialZoomLevel,oa.x=ca.center.x,oa.y=ca.center.y,a&&Ba("afterChange")},invalidateCurrItems:function(){x=!0;for(var a=0;h>a;a++)y[a].item&&(y[a].item.needsUpdate=!0)},updateCurrItem:function(a){if(0!==ta){var b,c=Math.abs(ta);if(!(a&&2>c)){f.currItem=Zb(m),Ba("beforeChange",ta),c>=h&&(o+=ta+(ta>0?-h:h),c=h);for(var d=0;c>d;d++)ta>0?(b=y.shift(),y[h-1]=b,o++,Ha((o+2)*sa.x,b.el.style),f.setContent(b,m-c+d+1+1)):(b=y.pop(),y.unshift(b),o--,Ha(o*sa.x,b.el.style),f.setContent(b,m+c-d-1-1));if(da&&1===Math.abs(ta)){var e=Zb(z);e.initialZoomLevel!==s&&(gc(e,pa),Ga(e))}ta=0,f.updateCurrZoomItem(),z=m,Ba("afterChange")}}},updateSize:function(b){if(!ua&&i.modal){var c=e.getScrollY();if(M!==c&&(a.style.top=c+"px",M=c),!b&&wa.x===window.innerWidth&&wa.y===window.innerHeight)return;wa.x=window.innerWidth,wa.y=window.innerHeight,a.style.height=wa.y+"px"}if(pa.x=f.scrollWrap.clientWidth,pa.y=f.scrollWrap.clientHeight,Xa(),sa.x=pa.x+Math.round(pa.x*i.spacing),sa.y=pa.y,Ia(sa.x*qa),Ba("beforeResize"),void 0!==o){for(var d,g,j,k=0;h>k;k++)d=y[k],Ha((k+o)*sa.x,d.el.style),j=m+k-1,i.loop&&$b()>2&&(j=ya(j)),g=Zb(j),g&&(x||g.needsUpdate||!g.bounds)?(f.cleanSlide(g),f.setContent(d,j),1===k&&(f.currItem=g,f.updateCurrZoomItem(!0)),g.needsUpdate=!1):-1===d.index&&j>=0&&f.setContent(d,j),g&&g.container&&(gc(g,pa),Ga(g));x=!1}t=s=f.currItem.initialZoomLevel,ca=f.currItem.bounds,ca&&(oa.x=ca.center.x,oa.y=ca.center.y,Fa()),Ba("resize")},zoomTo:function(a,b,c,d,f){b&&(t=s,sb.x=Math.abs(b.x)-oa.x,sb.y=Math.abs(b.y)-oa.y,Ka(na,oa));var g=Qa(a,!1),h={};Ta("x",g,h,a),Ta("y",g,h,a);var i=s,j={x:oa.x,y:oa.y};La(h);var k=function(b){1===b?(s=a,oa.x=h.x,oa.y=h.y):(s=(a-i)*b+i,oa.x=(h.x-j.x)*b+j.x,oa.y=(h.y-j.y)*b+j.y),f&&f(b),Fa()};c?bb("customZoomTo",0,1,c,d||e.easing.sine.inOut,k):k(1)}},db=30,eb=10,fb={},gb={},hb={},ib={},jb={},kb=[],lb={},mb=[],nb={},ob=0,pb=la(),qb=0,rb=la(),sb=la(),tb=la(),ub=function(a,b){return a.x===b.x&&a.y===b.y},vb=function(a,b){return Math.abs(a.x-b.x)<g&&Math.abs(a.y-b.y)<g},wb=function(a,b){return nb.x=Math.abs(a.x-b.x),nb.y=Math.abs(a.y-b.y),Math.sqrt(nb.x*nb.x+nb.y*nb.y)},xb=function(){Y&&(I(Y),Y=null)},yb=function(){U&&(Y=H(yb),Ob())},zb=function(){return!("fit"===i.scaleMode&&s===f.currItem.initialZoomLevel)},Ab=function(a,b){return a?a.className&&a.className.indexOf("pswp__scroll-wrap")>-1?!1:b(a)?a:Ab(a.parentNode,b):!1},Bb={},Cb=function(a,b){return Bb.prevent=!Ab(a.target,i.isClickableElement),Ba("preventDragEvent",a,b,Bb),Bb.prevent},Db=function(a,b){return b.x=a.pageX,b.y=a.pageY,b.id=a.identifier,b},Eb=function(a,b,c){c.x=.5*(a.x+b.x),c.y=.5*(a.y+b.y)},Fb=function(a,b,c){if(a-P>50){var d=mb.length>2?mb.shift():{};d.x=b,d.y=c,mb.push(d),P=a}},Gb=function(){var a=oa.y-f.currItem.initialPosition.y;return 1-Math.abs(a/(pa.y/2))},Hb={},Ib={},Jb=[],Kb=function(a){for(;Jb.length>0;)Jb.pop();return F?(ka=0,kb.forEach(function(a){0===ka?Jb[0]=a:1===ka&&(Jb[1]=a),ka++})):a.type.indexOf("touch")>-1?a.touches&&a.touches.length>0&&(Jb[0]=Db(a.touches[0],Hb),a.touches.length>1&&(Jb[1]=Db(a.touches[1],Ib))):(Hb.x=a.pageX,Hb.y=a.pageY,Hb.id="",Jb[0]=Hb),Jb},Lb=function(a,b){var c,d,e,g,h=0,j=oa[a]+b[a],k=b[a]>0,l=rb.x+b.x,m=rb.x-lb.x;return c=j>ca.min[a]||j<ca.max[a]?i.panEndFriction:1,j=oa[a]+b[a]*c,!i.allowPanToNext&&s!==f.currItem.initialZoomLevel||(da?"h"!==fa||"x"!==a||W||(k?(j>ca.min[a]&&(c=i.panEndFriction,h=ca.min[a]-j,d=ca.min[a]-na[a]),(0>=d||0>m)&&$b()>1?(g=l,0>m&&l>lb.x&&(g=lb.x)):ca.min.x!==ca.max.x&&(e=j)):(j<ca.max[a]&&(c=i.panEndFriction,h=j-ca.max[a],d=na[a]-ca.max[a]),(0>=d||m>0)&&$b()>1?(g=l,m>0&&l<lb.x&&(g=lb.x)):ca.min.x!==ca.max.x&&(e=j))):g=l,"x"!==a)?void(ea||Z||s>f.currItem.fitRatio&&(oa[a]+=b[a]*c)):(void 0!==g&&(Ia(g,!0),Z=g===lb.x?!1:!0),ca.min.x!==ca.max.x&&(void 0!==e?oa.x=e:Z||(oa.x+=b.x*c)),void 0!==g)},Mb=function(a){if(!("mousedown"===a.type&&a.button>0)){if(Yb)return void a.preventDefault();if(!T||"mousedown"!==a.type){if(Cb(a,!0)&&a.preventDefault(),Ba("pointerDown"),F){var b=e.arraySearch(kb,a.pointerId,"id");0>b&&(b=kb.length),kb[b]={x:a.pageX,y:a.pageY,id:a.pointerId}}var c=Kb(a),d=c.length;$=null,ab(),U&&1!==d||(U=ga=!0,e.bind(window,p,f),R=ja=ha=S=Z=X=V=W=!1,fa=null,Ba("firstTouchStart",c),Ka(na,oa),ma.x=ma.y=0,Ka(ib,c[0]),Ka(jb,ib),lb.x=sa.x*qa,mb=[{x:ib.x,y:ib.y}],P=O=Ca(),Qa(s,!0),xb(),yb()),!_&&d>1&&!ea&&!Z&&(t=s,W=!1,_=V=!0,ma.y=ma.x=0,Ka(na,oa),Ka(fb,c[0]),Ka(gb,c[1]),Eb(fb,gb,tb),sb.x=Math.abs(tb.x)-oa.x,sb.y=Math.abs(tb.y)-oa.y,aa=ba=wb(fb,gb))}}},Nb=function(a){if(a.preventDefault(),F){var b=e.arraySearch(kb,a.pointerId,"id");if(b>-1){var c=kb[b];c.x=a.pageX,c.y=a.pageY}}if(U){var d=Kb(a);if(fa||X||_)$=d;else{var f=Math.abs(d[0].x-ib.x)-Math.abs(d[0].y-ib.y);Math.abs(f)>=eb&&(fa=f>0?"h":"v",$=d)}}},Ob=function(){if($){var a=$.length;if(0!==a)if(Ka(fb,$[0]),hb.x=fb.x-ib.x,hb.y=fb.y-ib.y,_&&a>1){if(ib.x=fb.x,ib.y=fb.y,!hb.x&&!hb.y&&ub($[1],gb))return;Ka(gb,$[1]),W||(W=!0,Ba("zoomGestureStarted"));var b=wb(fb,gb),c=Tb(b);c>f.currItem.initialZoomLevel+f.currItem.initialZoomLevel/15&&(ja=!0);var d=1,e=Ra(),g=Sa();if(e>c)if(i.pinchToClose&&!ja&&t<=f.currItem.initialZoomLevel){var h=e-c,j=1-h/(e/1.2);Da(j),Ba("onPinchClose",j),ha=!0}else d=(e-c)/e,d>1&&(d=1),c=e-d*(e/3);else c>g&&(d=(c-g)/(6*e),d>1&&(d=1),c=g+d*e);0>d&&(d=0),aa=b,Eb(fb,gb,pb),ma.x+=pb.x-tb.x,ma.y+=pb.y-tb.y,Ka(tb,pb),oa.x=Ja("x",c),oa.y=Ja("y",c),R=c>s,s=c,Fa()}else{if(!fa)return;if(ga&&(ga=!1,Math.abs(hb.x)>=eb&&(hb.x-=$[0].x-jb.x),Math.abs(hb.y)>=eb&&(hb.y-=$[0].y-jb.y)),ib.x=fb.x,ib.y=fb.y,0===hb.x&&0===hb.y)return;if("v"===fa&&i.closeOnVerticalDrag&&!zb()){ma.y+=hb.y,oa.y+=hb.y;var k=Gb();return S=!0,Ba("onVerticalDrag",k),Da(k),void Fa()}Fb(Ca(),fb.x,fb.y),X=!0,ca=f.currItem.bounds;var l=Lb("x",hb);l||(Lb("y",hb),La(oa),Fa())}}},Pb=function(a){if(N.isOldAndroid){if(T&&"mouseup"===a.type)return;a.type.indexOf("touch")>-1&&(clearTimeout(T),T=setTimeout(function(){T=0},600))}Ba("pointerUp"),Cb(a,!1)&&a.preventDefault();var b;if(F){var c=e.arraySearch(kb,a.pointerId,"id");if(c>-1)if(b=kb.splice(c,1)[0],navigator.pointerEnabled)b.type=a.pointerType||"mouse";else{var d={4:"mouse",2:"touch",3:"pen"};b.type=d[a.pointerType],b.type||(b.type=a.pointerType||"mouse")}}var g,h=Kb(a),j=h.length;if("mouseup"===a.type&&(j=0),2===j)return $=null,!0;1===j&&Ka(jb,h[0]),0!==j||fa||ea||(b||("mouseup"===a.type?b={x:a.pageX,y:a.pageY,type:"mouse"}:a.changedTouches&&a.changedTouches[0]&&(b={x:a.changedTouches[0].pageX,y:a.changedTouches[0].pageY,type:"touch"})),Ba("touchRelease",a,b));var k=-1;if(0===j&&(U=!1,e.unbind(window,p,f),xb(),_?k=0:-1!==qb&&(k=Ca()-qb)),qb=1===j?Ca():-1,g=-1!==k&&150>k?"zoom":"swipe",_&&2>j&&(_=!1,1===j&&(g="zoomPointerUp"),Ba("zoomGestureEnded")),$=null,X||W||ea||S)if(ab(),Q||(Q=Qb()),Q.calculateSwipeSpeed("x"),S){var l=Gb();if(l<i.verticalDragRange)f.close();else{var m=oa.y,n=ia;bb("verticalDrag",0,1,300,e.easing.cubic.out,function(a){oa.y=(f.currItem.initialPosition.y-m)*a+m,Da((1-n)*a+n),Fa()}),Ba("onVerticalDrag",1)}}else{if((Z||ea)&&0===j){var o=Sb(g,Q);if(o)return;g="zoomPointerUp"}if(!ea)return"swipe"!==g?void Ub():void(!Z&&s>f.currItem.fitRatio&&Rb(Q))}},Qb=function(){var a,b,c={lastFlickOffset:{},lastFlickDist:{},lastFlickSpeed:{},slowDownRatio:{},slowDownRatioReverse:{},speedDecelerationRatio:{},speedDecelerationRatioAbs:{},distanceOffset:{},backAnimDestination:{},backAnimStarted:{},calculateSwipeSpeed:function(d){mb.length>1?(a=Ca()-P+50,b=mb[mb.length-2][d]):(a=Ca()-O,b=jb[d]),c.lastFlickOffset[d]=ib[d]-b,c.lastFlickDist[d]=Math.abs(c.lastFlickOffset[d]),c.lastFlickDist[d]>20?c.lastFlickSpeed[d]=c.lastFlickOffset[d]/a:c.lastFlickSpeed[d]=0,Math.abs(c.lastFlickSpeed[d])<.1&&(c.lastFlickSpeed[d]=0),c.slowDownRatio[d]=.95,c.slowDownRatioReverse[d]=1-c.slowDownRatio[d],c.speedDecelerationRatio[d]=1},calculateOverBoundsAnimOffset:function(a,b){c.backAnimStarted[a]||(oa[a]>ca.min[a]?c.backAnimDestination[a]=ca.min[a]:oa[a]<ca.max[a]&&(c.backAnimDestination[a]=ca.max[a]),void 0!==c.backAnimDestination[a]&&(c.slowDownRatio[a]=.7,c.slowDownRatioReverse[a]=1-c.slowDownRatio[a],c.speedDecelerationRatioAbs[a]<.05&&(c.lastFlickSpeed[a]=0,c.backAnimStarted[a]=!0,bb("bounceZoomPan"+a,oa[a],c.backAnimDestination[a],b||300,e.easing.sine.out,function(b){oa[a]=b,Fa()}))))},calculateAnimOffset:function(a){c.backAnimStarted[a]||(c.speedDecelerationRatio[a]=c.speedDecelerationRatio[a]*(c.slowDownRatio[a]+c.slowDownRatioReverse[a]-c.slowDownRatioReverse[a]*c.timeDiff/10),c.speedDecelerationRatioAbs[a]=Math.abs(c.lastFlickSpeed[a]*c.speedDecelerationRatio[a]),c.distanceOffset[a]=c.lastFlickSpeed[a]*c.speedDecelerationRatio[a]*c.timeDiff,oa[a]+=c.distanceOffset[a])},panAnimLoop:function(){return Ya.zoomPan&&(Ya.zoomPan.raf=H(c.panAnimLoop),c.now=Ca(),c.timeDiff=c.now-c.lastNow,c.lastNow=c.now,c.calculateAnimOffset("x"),c.calculateAnimOffset("y"),Fa(),c.calculateOverBoundsAnimOffset("x"),c.calculateOverBoundsAnimOffset("y"),c.speedDecelerationRatioAbs.x<.05&&c.speedDecelerationRatioAbs.y<.05)?(oa.x=Math.round(oa.x),oa.y=Math.round(oa.y),Fa(),void $a("zoomPan")):void 0}};return c},Rb=function(a){return a.calculateSwipeSpeed("y"),ca=f.currItem.bounds,a.backAnimDestination={},a.backAnimStarted={},Math.abs(a.lastFlickSpeed.x)<=.05&&Math.abs(a.lastFlickSpeed.y)<=.05?(a.speedDecelerationRatioAbs.x=a.speedDecelerationRatioAbs.y=0,a.calculateOverBoundsAnimOffset("x"),a.calculateOverBoundsAnimOffset("y"),!0):(_a("zoomPan"),a.lastNow=Ca(),void a.panAnimLoop())},Sb=function(a,b){var c;ea||(ob=m);var d;if("swipe"===a){var g=ib.x-jb.x,h=b.lastFlickDist.x<10;g>db&&(h||b.lastFlickOffset.x>20)?d=-1:-db>g&&(h||b.lastFlickOffset.x<-20)&&(d=1)}var j;d&&(m+=d,0>m?(m=i.loop?$b()-1:0,j=!0):m>=$b()&&(m=i.loop?0:$b()-1,j=!0),(!j||i.loop)&&(ta+=d,qa-=d,c=!0));var k,l=sa.x*qa,n=Math.abs(l-rb.x);return c||l>rb.x==b.lastFlickSpeed.x>0?(k=Math.abs(b.lastFlickSpeed.x)>0?n/Math.abs(b.lastFlickSpeed.x):333,k=Math.min(k,400),k=Math.max(k,250)):k=333,ob===m&&(c=!1),ea=!0,Ba("mainScrollAnimStart"),bb("mainScroll",rb.x,l,k,e.easing.cubic.out,Ia,function(){ab(),ea=!1,ob=-1,(c||ob!==m)&&f.updateCurrItem(),Ba("mainScrollAnimComplete")}),c&&f.updateCurrItem(!0),c},Tb=function(a){return 1/ba*a*t},Ub=function(){var a=s,b=Ra(),c=Sa();b>s?a=b:s>c&&(a=c);var d,g=1,h=ia;return ha&&!R&&!ja&&b>s?(f.close(),!0):(ha&&(d=function(a){Da((g-h)*a+h)}),f.zoomTo(a,0,300,e.easing.cubic.out,d),!0)};xa("Gestures",{publicMethods:{initGestures:function(){var a=function(a,b,c,d,e){A=a+b,B=a+c,C=a+d,D=e?a+e:""};F=N.pointerEvent,F&&N.touch&&(N.touch=!1),F?navigator.pointerEnabled?a("pointer","down","move","up","cancel"):a("MSPointer","Down","Move","Up","Cancel"):N.touch?(a("touch","start","move","end","cancel"),G=!0):a("mouse","down","move","up"),p=B+" "+C+" "+D,q=A,F&&!G&&(G=navigator.maxTouchPoints>1||navigator.msMaxTouchPoints>1),f.likelyTouchDevice=G,r[A]=Mb,r[B]=Nb,r[C]=Pb,D&&(r[D]=r[C]),N.touch&&(q+=" mousedown",p+=" mousemove mouseup",r.mousedown=r[A],r.mousemove=r[B],r.mouseup=r[C]),G||(i.allowPanToNext=!1)}}});var Vb,Wb,Xb,Yb,Zb,$b,_b,ac=function(b,c,d,g){Vb&&clearTimeout(Vb),Yb=!0,Xb=!0;var h;b.initialLayout?(h=b.initialLayout,b.initialLayout=null):h=i.getThumbBoundsFn&&i.getThumbBoundsFn(m);var j=d?i.hideAnimationDuration:i.showAnimationDuration,k=function(){$a("initialZoom"),d?(f.template.removeAttribute("style"),f.bg.removeAttribute("style")):(Da(1),c&&(c.style.display="block"),e.addClass(a,"pswp--animated-in"),Ba("initialZoom"+(d?"OutEnd":"InEnd"))),g&&g(),Yb=!1};if(!j||!h||void 0===h.x){var n=function(){Ba("initialZoom"+(d?"Out":"In")),s=b.initialZoomLevel,Ka(oa,b.initialPosition),Fa(),a.style.opacity=d?0:1,Da(1),k()};return void n()}var o=function(){var c=l,g=!f.currItem.src||f.currItem.loadError||i.showHideOpacity;b.miniImg&&(b.miniImg.style.webkitBackfaceVisibility="hidden"),d||(s=h.w/b.w,oa.x=h.x,oa.y=h.y-K,f[g?"template":"bg"].style.opacity=.001,Fa()),_a("initialZoom"),d&&!c&&e.removeClass(a,"pswp--animated-in"),g&&(d?e[(c?"remove":"add")+"Class"](a,"pswp--animate_opacity"):setTimeout(function(){e.addClass(a,"pswp--animate_opacity")},30)),Vb=setTimeout(function(){if(Ba("initialZoom"+(d?"Out":"In")),d){var f=h.w/b.w,i={x:oa.x,y:oa.y},l=s,m=ia,n=function(b){1===b?(s=f,oa.x=h.x,oa.y=h.y-M):(s=(f-l)*b+l,oa.x=(h.x-i.x)*b+i.x,oa.y=(h.y-M-i.y)*b+i.y),Fa(),g?a.style.opacity=1-b:Da(m-b*m)};c?bb("initialZoom",0,1,j,e.easing.cubic.out,n,k):(n(1),Vb=setTimeout(k,j+20))}else s=b.initialZoomLevel,Ka(oa,b.initialPosition),Fa(),Da(1),g?a.style.opacity=1:Da(1),Vb=setTimeout(k,j+20)},d?25:90)};o()},bc={},cc=[],dc={index:0,errorMsg:'<div class="pswp__error-msg"><a href="%url%" target="_blank">The image</a> could not be loaded.</div>',forceProgressiveLoading:!1,preload:[1,1],getNumItemsFn:function(){return Wb.length}},ec=function(){return{center:{x:0,y:0},max:{x:0,y:0},min:{x:0,y:0}}},fc=function(a,b,c){var d=a.bounds;d.center.x=Math.round((bc.x-b)/2),d.center.y=Math.round((bc.y-c)/2)+a.vGap.top,d.max.x=b>bc.x?Math.round(bc.x-b):d.center.x,d.max.y=c>bc.y?Math.round(bc.y-c)+a.vGap.top:d.center.y,d.min.x=b>bc.x?0:d.center.x,d.min.y=c>bc.y?a.vGap.top:d.center.y},gc=function(a,b,c){if(a.src&&!a.loadError){var d=!c;if(d&&(a.vGap||(a.vGap={top:0,bottom:0}),Ba("parseVerticalMargin",a)),bc.x=b.x,bc.y=b.y-a.vGap.top-a.vGap.bottom,d){var e=bc.x/a.w,f=bc.y/a.h;a.fitRatio=f>e?e:f;var g=i.scaleMode;"orig"===g?c=1:"fit"===g&&(c=a.fitRatio),c>1&&(c=1),a.initialZoomLevel=c,a.bounds||(a.bounds=ec())}if(!c)return;return fc(a,a.w*c,a.h*c),d&&c===a.initialZoomLevel&&(a.initialPosition=a.bounds.center),a.bounds}return a.w=a.h=0,a.initialZoomLevel=a.fitRatio=1,a.bounds=ec(),a.initialPosition=a.bounds.center,a.bounds},hc=function(a,b,c,d,e,g){if(!b.loadError){var h,j=f.isDragging()&&!f.isZooming(),k=a===m||f.isMainScrollAnimating()||j;!e&&(G||i.alwaysFadeIn)&&k&&(h=!0),d&&(h&&(d.style.opacity=0),b.imageAppended=!0,kc(d,b.w,b.h),c.appendChild(d),h&&setTimeout(function(){d.style.opacity=1,g&&setTimeout(function(){b&&b.loaded&&b.placeholder&&(b.placeholder.style.display="none",b.placeholder=null)},500)},50))}},ic=function(a){a.loading=!0,a.loaded=!1;var b=a.img=e.createEl("pswp__img","img"),c=function(){a.loading=!1,a.loaded=!0,a.loadComplete?a.loadComplete(a):a.img=null,b.onload=b.onerror=null,b=null};return b.onload=c,b.onerror=function(){a.loadError=!0,c()},b.src=a.src,b},jc=function(a,b){return a.src&&a.loadError&&a.container?(b&&(a.container.innerHTML=""),a.container.innerHTML=i.errorMsg.replace("%url%",a.src),!0):void 0},kc=function(a,b,c){a.style.width=b+"px",a.style.height=c+"px"},lc=function(){if(cc.length){for(var a,b=0;b<cc.length;b++)a=cc[b],a.holder.index===a.index&&hc(a.index,a.item,a.baseDiv,a.img);cc=[]}};xa("Controller",{publicMethods:{lazyLoadItem:function(a){a=ya(a);var b=Zb(a);!b||b.loaded||b.loading||(Ba("gettingData",a,b),b.src&&ic(b))},initController:function(){e.extend(i,dc,!0),f.items=Wb=c,Zb=f.getItemAt,$b=i.getNumItemsFn,_b=i.loop,$b()<3&&(i.loop=!1),Aa("beforeChange",function(a){var b,c=i.preload,d=null===a?!0:a>0,e=Math.min(c[0],$b()),g=Math.min(c[1],$b());for(b=1;(d?g:e)>=b;b++)f.lazyLoadItem(m+b);for(b=1;(d?e:g)>=b;b++)f.lazyLoadItem(m-b)}),Aa("initialLayout",function(){f.currItem.initialLayout=i.getThumbBoundsFn&&i.getThumbBoundsFn(m)}),Aa("mainScrollAnimComplete",lc),Aa("initialZoomInEnd",lc),Aa("destroy",function(){for(var a,b=0;b<Wb.length;b++)a=Wb[b],a.container&&(a.container=null),a.placeholder&&(a.placeholder=null),a.img&&(a.img=null),a.preloader&&(a.preloader=null),a.loadError&&(a.loaded=a.loadError=!1);cc=null})},getItemAt:function(a){return a>=0&&void 0!==Wb[a]?Wb[a]:!1},allowProgressiveImg:function(){return i.forceProgressiveLoading||!G||i.mouseUsed||screen.width>1200},setContent:function(a,b){i.loop&&(b=ya(b));var c=f.getItemAt(a.index);c&&(c.container=null);var d,g=f.getItemAt(b);if(!g)return void(a.el.innerHTML="");Ba("gettingData",b,g),a.index=b,a.item=g;var h=g.container=e.createEl("pswp__zoom-wrap");if(!g.src&&g.html&&(g.html.tagName?h.appendChild(g.html):h.innerHTML=g.html),jc(g),!g.src||g.loadError||g.loaded)g.src&&!g.loadError&&(d=e.createEl("pswp__img","img"),d.style.webkitBackfaceVisibility="hidden",d.style.opacity=1,d.src=g.src,kc(d,g.w,g.h),hc(b,g,h,d,!0));else{if(g.loadComplete=function(c){if(j){if(c.img&&(c.img.style.webkitBackfaceVisibility="hidden"),a&&a.index===b){if(jc(c,!0))return c.loadComplete=c.img=null,gc(c,pa),Ga(c),void(a.index===m&&f.updateCurrZoomItem());c.imageAppended?!Yb&&c.placeholder&&(c.placeholder.style.display="none",c.placeholder=null):N.transform&&(ea||Yb)?cc.push({item:c,baseDiv:h,img:c.img,index:b,holder:a}):hc(b,c,h,c.img,ea||Yb)}c.loadComplete=null,c.img=null,Ba("imageLoadComplete",b,c)}},e.features.transform){var k="pswp__img pswp__img--placeholder";k+=g.msrc?"":" pswp__img--placeholder--blank";var l=e.createEl(k,g.msrc?"img":"");g.msrc&&(l.src=g.msrc),kc(l,g.w,g.h),h.appendChild(l),g.placeholder=l}g.loading||ic(g),f.allowProgressiveImg()&&(!Xb&&N.transform?cc.push({item:g,baseDiv:h,img:g.img,index:b,holder:a}):hc(b,g,h,g.img,!0,!0))}gc(g,pa),Xb||b!==m?Ga(g):(da=h.style,ac(g,d||g.img)),a.el.innerHTML="",a.el.appendChild(h)},cleanSlide:function(a){a.img&&(a.img.onload=a.img.onerror=null),a.loaded=a.loading=a.img=a.imageAppended=!1}}});var mc,nc={},oc=function(a,b,c){var d=document.createEvent("CustomEvent"),e={origEvent:a,target:a.target,releasePoint:b,pointerType:c||"touch"};d.initCustomEvent("pswpTap",!0,!0,e),a.target.dispatchEvent(d)};xa("Tap",{publicMethods:{initTap:function(){Aa("firstTouchStart",f.onTapStart),Aa("touchRelease",f.onTapRelease),Aa("destroy",function(){nc={},mc=null})},onTapStart:function(a){a.length>1&&(clearTimeout(mc),mc=null)},onTapRelease:function(a,b){if(b&&!X&&!V&&!Za){var c=b;if(mc&&(clearTimeout(mc),mc=null,vb(c,nc)))return void Ba("doubleTap",c);if("mouse"===b.type)return void oc(a,b,"mouse");var d=a.target.tagName.toUpperCase();if("BUTTON"===d||e.hasClass(a.target,"pswp__single-tap"))return void oc(a,b);Ka(nc,c),mc=setTimeout(function(){oc(a,b),mc=null},300)}}}});var pc;xa("DesktopZoom",{publicMethods:{initDesktopZoom:function(){L||(G?Aa("mouseUsed",function(){f.setupDesktopZoom()}):f.setupDesktopZoom(!0))},setupDesktopZoom:function(b){pc={};var c="wheel mousewheel DOMMouseScroll";Aa("bindEvents",function(){e.bind(a,c,f.handleMouseWheel)}),Aa("unbindEvents",function(){pc&&e.unbind(a,c,f.handleMouseWheel)}),f.mouseZoomedIn=!1;var d,g=function(){f.mouseZoomedIn&&(e.removeClass(a,"pswp--zoomed-in"),f.mouseZoomedIn=!1),1>s?e.addClass(a,"pswp--zoom-allowed"):e.removeClass(a,"pswp--zoom-allowed"),h()},h=function(){d&&(e.removeClass(a,"pswp--dragging"),d=!1)};Aa("resize",g),Aa("afterChange",g),Aa("pointerDown",function(){f.mouseZoomedIn&&(d=!0,e.addClass(a,"pswp--dragging"))}),Aa("pointerUp",h),b||g()},handleMouseWheel:function(a){if(s<=f.currItem.fitRatio)return i.modal&&(i.closeOnScroll?E&&Math.abs(a.deltaY)>2&&(l=!0,f.close()):a.preventDefault()),!0;if(a.stopPropagation(),pc.x=0,"deltaX"in a)1===a.deltaMode?(pc.x=18*a.deltaX,pc.y=18*a.deltaY):(pc.x=a.deltaX,pc.y=a.deltaY);else if("wheelDelta"in a)a.wheelDeltaX&&(pc.x=-.16*a.wheelDeltaX),a.wheelDeltaY?pc.y=-.16*a.wheelDeltaY:pc.y=-.16*a.wheelDelta;else{if(!("detail"in a))return;pc.y=a.detail}Qa(s,!0);var b=oa.x-pc.x,c=oa.y-pc.y;(i.modal||b<=ca.min.x&&b>=ca.max.x&&c<=ca.min.y&&c>=ca.max.y)&&a.preventDefault(),f.panTo(b,c)},toggleDesktopZoom:function(b){b=b||{x:pa.x/2+ra.x,y:pa.y/2+ra.y};var c=i.getDoubleTapZoom(!0,f.currItem),d=s===c;f.mouseZoomedIn=!d,f.zoomTo(d?f.currItem.initialZoomLevel:c,b,333),e[(d?"remove":"add")+"Class"](a,"pswp--zoomed-in")}}});var qc,rc,sc,tc,uc,vc,wc,xc,yc,zc,Ac,Bc,Cc={history:!0,galleryUID:1},Dc=function(){return Ac.hash.substring(1)},Ec=function(){qc&&clearTimeout(qc),sc&&clearTimeout(sc)},Fc=function(){var a=Dc(),b={};if(a.length<5)return b;var c,d=a.split("&");for(c=0;c<d.length;c++)if(d[c]){var e=d[c].split("=");e.length<2||(b[e[0]]=e[1])}if(i.galleryPIDs){var f=b.pid;for(b.pid=0,c=0;c<Wb.length;c++)if(Wb[c].pid===f){b.pid=c;break}}else b.pid=parseInt(b.pid,10)-1;return b.pid<0&&(b.pid=0),b},Gc=function(){if(sc&&clearTimeout(sc),Za||U)return void(sc=setTimeout(Gc,500));tc?clearTimeout(rc):tc=!0;var a=m+1,b=Zb(m);b.hasOwnProperty("pid")&&(a=b.pid);var c=wc+"&gid="+i.galleryUID+"&pid="+a;xc||-1===Ac.hash.indexOf(c)&&(zc=!0);var d=Ac.href.split("#")[0]+"#"+c;Bc?"#"+c!==window.location.hash&&history[xc?"replaceState":"pushState"]("",document.title,d):xc?Ac.replace(d):Ac.hash=c,xc=!0,rc=setTimeout(function(){tc=!1},60)};xa("History",{publicMethods:{initHistory:function(){if(e.extend(i,Cc,!0),i.history){Ac=window.location,zc=!1,yc=!1,xc=!1,wc=Dc(),Bc="pushState"in history,wc.indexOf("gid=")>-1&&(wc=wc.split("&gid=")[0],wc=wc.split("?gid=")[0]),Aa("afterChange",f.updateURL),Aa("unbindEvents",function(){e.unbind(window,"hashchange",f.onHashChange)});var a=function(){vc=!0,yc||(zc?history.back():wc?Ac.hash=wc:Bc?history.pushState("",document.title,Ac.pathname+Ac.search):Ac.hash=""),Ec()};Aa("unbindEvents",function(){l&&a()}),Aa("destroy",function(){vc||a()}),Aa("firstUpdate",function(){m=Fc().pid});var b=wc.indexOf("pid=");b>-1&&(wc=wc.substring(0,b),"&"===wc.slice(-1)&&(wc=wc.slice(0,-1))),setTimeout(function(){j&&e.bind(window,"hashchange",f.onHashChange)},40)}},onHashChange:function(){return Dc()===wc?(yc=!0,void f.close()):void(tc||(uc=!0,f.goTo(Fc().pid),uc=!1))},updateURL:function(){Ec(),uc||(xc?qc=setTimeout(Gc,800):Gc())}}}),e.extend(f,cb)};return a});
/*! ScrollMagic v2.0.5 | (c) 2015 Jan Paepke (@janpaepke) | license & info: http://scrollmagic.io */

!function(e,t){"function"==typeof define&&define.amd?define(t):"object"==typeof exports?module.exports=t():e.ScrollMagic=t()}(this,function(){"use strict";var e=function(){};e.version="2.0.5",window.addEventListener("mousewheel",function(){});var t="data-scrollmagic-pin-spacer";e.Controller=function(r){var o,s,a="ScrollMagic.Controller",l="FORWARD",c="REVERSE",u="PAUSED",f=n.defaults,d=this,h=i.extend({},f,r),g=[],p=!1,v=0,m=u,w=!0,y=0,S=!0,b=function(){for(var e in h)f.hasOwnProperty(e)||delete h[e];if(h.container=i.get.elements(h.container)[0],!h.container)throw a+" init failed.";w=h.container===window||h.container===document.body||!document.body.contains(h.container),w&&(h.container=window),y=z(),h.container.addEventListener("resize",T),h.container.addEventListener("scroll",T),h.refreshInterval=parseInt(h.refreshInterval)||f.refreshInterval,E()},E=function(){h.refreshInterval>0&&(s=window.setTimeout(A,h.refreshInterval))},x=function(){return h.vertical?i.get.scrollTop(h.container):i.get.scrollLeft(h.container)},z=function(){return h.vertical?i.get.height(h.container):i.get.width(h.container)},C=this._setScrollPos=function(e){h.vertical?w?window.scrollTo(i.get.scrollLeft(),e):h.container.scrollTop=e:w?window.scrollTo(e,i.get.scrollTop()):h.container.scrollLeft=e},F=function(){if(S&&p){var e=i.type.Array(p)?p:g.slice(0);p=!1;var t=v;v=d.scrollPos();var n=v-t;0!==n&&(m=n>0?l:c),m===c&&e.reverse(),e.forEach(function(e){e.update(!0)})}},L=function(){o=i.rAF(F)},T=function(e){"resize"==e.type&&(y=z(),m=u),p!==!0&&(p=!0,L())},A=function(){if(!w&&y!=z()){var e;try{e=new Event("resize",{bubbles:!1,cancelable:!1})}catch(t){e=document.createEvent("Event"),e.initEvent("resize",!1,!1)}h.container.dispatchEvent(e)}g.forEach(function(e){e.refresh()}),E()};this._options=h;var O=function(e){if(e.length<=1)return e;var t=e.slice(0);return t.sort(function(e,t){return e.scrollOffset()>t.scrollOffset()?1:-1}),t};return this.addScene=function(t){if(i.type.Array(t))t.forEach(function(e){d.addScene(e)});else if(t instanceof e.Scene)if(t.controller()!==d)t.addTo(d);else if(g.indexOf(t)<0){g.push(t),g=O(g),t.on("shift.controller_sort",function(){g=O(g)});for(var n in h.globalSceneOptions)t[n]&&t[n].call(t,h.globalSceneOptions[n])}return d},this.removeScene=function(e){if(i.type.Array(e))e.forEach(function(e){d.removeScene(e)});else{var t=g.indexOf(e);t>-1&&(e.off("shift.controller_sort"),g.splice(t,1),e.remove())}return d},this.updateScene=function(t,n){return i.type.Array(t)?t.forEach(function(e){d.updateScene(e,n)}):n?t.update(!0):p!==!0&&t instanceof e.Scene&&(p=p||[],-1==p.indexOf(t)&&p.push(t),p=O(p),L()),d},this.update=function(e){return T({type:"resize"}),e&&F(),d},this.scrollTo=function(n,r){if(i.type.Number(n))C.call(h.container,n,r);else if(n instanceof e.Scene)n.controller()===d&&d.scrollTo(n.scrollOffset(),r);else if(i.type.Function(n))C=n;else{var o=i.get.elements(n)[0];if(o){for(;o.parentNode.hasAttribute(t);)o=o.parentNode;var s=h.vertical?"top":"left",a=i.get.offset(h.container),l=i.get.offset(o);w||(a[s]-=d.scrollPos()),d.scrollTo(l[s]-a[s],r)}}return d},this.scrollPos=function(e){return arguments.length?(i.type.Function(e)&&(x=e),d):x.call(d)},this.info=function(e){var t={size:y,vertical:h.vertical,scrollPos:v,scrollDirection:m,container:h.container,isDocument:w};return arguments.length?void 0!==t[e]?t[e]:void 0:t},this.loglevel=function(){return d},this.enabled=function(e){return arguments.length?(S!=e&&(S=!!e,d.updateScene(g,!0)),d):S},this.destroy=function(e){window.clearTimeout(s);for(var t=g.length;t--;)g[t].destroy(e);return h.container.removeEventListener("resize",T),h.container.removeEventListener("scroll",T),i.cAF(o),null},b(),d};var n={defaults:{container:window,vertical:!0,globalSceneOptions:{},loglevel:2,refreshInterval:100}};e.Controller.addOption=function(e,t){n.defaults[e]=t},e.Controller.extend=function(t){var n=this;e.Controller=function(){return n.apply(this,arguments),this.$super=i.extend({},this),t.apply(this,arguments)||this},i.extend(e.Controller,n),e.Controller.prototype=n.prototype,e.Controller.prototype.constructor=e.Controller},e.Scene=function(n){var o,s,a="BEFORE",l="DURING",c="AFTER",u=r.defaults,f=this,d=i.extend({},u,n),h=a,g=0,p={start:0,end:0},v=0,m=!0,w=function(){for(var e in d)u.hasOwnProperty(e)||delete d[e];for(var t in u)L(t);C()},y={};this.on=function(e,t){return i.type.Function(t)&&(e=e.trim().split(" "),e.forEach(function(e){var n=e.split("."),r=n[0],i=n[1];"*"!=r&&(y[r]||(y[r]=[]),y[r].push({namespace:i||"",callback:t}))})),f},this.off=function(e,t){return e?(e=e.trim().split(" "),e.forEach(function(e){var n=e.split("."),r=n[0],i=n[1]||"",o="*"===r?Object.keys(y):[r];o.forEach(function(e){for(var n=y[e]||[],r=n.length;r--;){var o=n[r];!o||i!==o.namespace&&"*"!==i||t&&t!=o.callback||n.splice(r,1)}n.length||delete y[e]})}),f):f},this.trigger=function(t,n){if(t){var r=t.trim().split("."),i=r[0],o=r[1],s=y[i];s&&s.forEach(function(t){o&&o!==t.namespace||t.callback.call(f,new e.Event(i,t.namespace,f,n))})}return f},f.on("change.internal",function(e){"loglevel"!==e.what&&"tweenChanges"!==e.what&&("triggerElement"===e.what?E():"reverse"===e.what&&f.update())}).on("shift.internal",function(){S(),f.update()}),this.addTo=function(t){return t instanceof e.Controller&&s!=t&&(s&&s.removeScene(f),s=t,C(),b(!0),E(!0),S(),s.info("container").addEventListener("resize",x),t.addScene(f),f.trigger("add",{controller:s}),f.update()),f},this.enabled=function(e){return arguments.length?(m!=e&&(m=!!e,f.update(!0)),f):m},this.remove=function(){if(s){s.info("container").removeEventListener("resize",x);var e=s;s=void 0,e.removeScene(f),f.trigger("remove")}return f},this.destroy=function(e){return f.trigger("destroy",{reset:e}),f.remove(),f.off("*.*"),null},this.update=function(e){if(s)if(e)if(s.enabled()&&m){var t,n=s.info("scrollPos");t=d.duration>0?(n-p.start)/(p.end-p.start):n>=p.start?1:0,f.trigger("update",{startPos:p.start,endPos:p.end,scrollPos:n}),f.progress(t)}else T&&h===l&&O(!0);else s.updateScene(f,!1);return f},this.refresh=function(){return b(),E(),f},this.progress=function(e){if(arguments.length){var t=!1,n=h,r=s?s.info("scrollDirection"):"PAUSED",i=d.reverse||e>=g;if(0===d.duration?(t=g!=e,g=1>e&&i?0:1,h=0===g?a:l):0>e&&h!==a&&i?(g=0,h=a,t=!0):e>=0&&1>e&&i?(g=e,h=l,t=!0):e>=1&&h!==c?(g=1,h=c,t=!0):h!==l||i||O(),t){var o={progress:g,state:h,scrollDirection:r},u=h!=n,p=function(e){f.trigger(e,o)};u&&n!==l&&(p("enter"),p(n===a?"start":"end")),p("progress"),u&&h!==l&&(p(h===a?"start":"end"),p("leave"))}return f}return g};var S=function(){p={start:v+d.offset},s&&d.triggerElement&&(p.start-=s.info("size")*d.triggerHook),p.end=p.start+d.duration},b=function(e){if(o){var t="duration";F(t,o.call(f))&&!e&&(f.trigger("change",{what:t,newval:d[t]}),f.trigger("shift",{reason:t}))}},E=function(e){var n=0,r=d.triggerElement;if(s&&r){for(var o=s.info(),a=i.get.offset(o.container),l=o.vertical?"top":"left";r.parentNode.hasAttribute(t);)r=r.parentNode;var c=i.get.offset(r);o.isDocument||(a[l]-=s.scrollPos()),n=c[l]-a[l]}var u=n!=v;v=n,u&&!e&&f.trigger("shift",{reason:"triggerElementPosition"})},x=function(){d.triggerHook>0&&f.trigger("shift",{reason:"containerResize"})},z=i.extend(r.validate,{duration:function(e){if(i.type.String(e)&&e.match(/^(\.|\d)*\d+%$/)){var t=parseFloat(e)/100;e=function(){return s?s.info("size")*t:0}}if(i.type.Function(e)){o=e;try{e=parseFloat(o())}catch(n){e=-1}}if(e=parseFloat(e),!i.type.Number(e)||0>e)throw o?(o=void 0,0):0;return e}}),C=function(e){e=arguments.length?[e]:Object.keys(z),e.forEach(function(e){var t;if(z[e])try{t=z[e](d[e])}catch(n){t=u[e]}finally{d[e]=t}})},F=function(e,t){var n=!1,r=d[e];return d[e]!=t&&(d[e]=t,C(e),n=r!=d[e]),n},L=function(e){f[e]||(f[e]=function(t){return arguments.length?("duration"===e&&(o=void 0),F(e,t)&&(f.trigger("change",{what:e,newval:d[e]}),r.shifts.indexOf(e)>-1&&f.trigger("shift",{reason:e})),f):d[e]})};this.controller=function(){return s},this.state=function(){return h},this.scrollOffset=function(){return p.start},this.triggerPosition=function(){var e=d.offset;return s&&(e+=d.triggerElement?v:s.info("size")*f.triggerHook()),e};var T,A;f.on("shift.internal",function(e){var t="duration"===e.reason;(h===c&&t||h===l&&0===d.duration)&&O(),t&&_()}).on("progress.internal",function(){O()}).on("add.internal",function(){_()}).on("destroy.internal",function(e){f.removePin(e.reset)});var O=function(e){if(T&&s){var t=s.info(),n=A.spacer.firstChild;if(e||h!==l){var r={position:A.inFlow?"relative":"absolute",top:0,left:0},o=i.css(n,"position")!=r.position;A.pushFollowers?d.duration>0&&(h===c&&0===parseFloat(i.css(A.spacer,"padding-top"))?o=!0:h===a&&0===parseFloat(i.css(A.spacer,"padding-bottom"))&&(o=!0)):r[t.vertical?"top":"left"]=d.duration*g,i.css(n,r),o&&_()}else{"fixed"!=i.css(n,"position")&&(i.css(n,{position:"fixed"}),_());var u=i.get.offset(A.spacer,!0),f=d.reverse||0===d.duration?t.scrollPos-p.start:Math.round(g*d.duration*10)/10;u[t.vertical?"top":"left"]+=f,i.css(A.spacer.firstChild,{top:u.top,left:u.left})}}},_=function(){if(T&&s&&A.inFlow){var e=h===l,t=s.info("vertical"),n=A.spacer.firstChild,r=i.isMarginCollapseType(i.css(A.spacer,"display")),o={};A.relSize.width||A.relSize.autoFullWidth?e?i.css(T,{width:i.get.width(A.spacer)}):i.css(T,{width:"100%"}):(o["min-width"]=i.get.width(t?T:n,!0,!0),o.width=e?o["min-width"]:"auto"),A.relSize.height?e?i.css(T,{height:i.get.height(A.spacer)-(A.pushFollowers?d.duration:0)}):i.css(T,{height:"100%"}):(o["min-height"]=i.get.height(t?n:T,!0,!r),o.height=e?o["min-height"]:"auto"),A.pushFollowers&&(o["padding"+(t?"Top":"Left")]=d.duration*g,o["padding"+(t?"Bottom":"Right")]=d.duration*(1-g)),i.css(A.spacer,o)}},N=function(){s&&T&&h===l&&!s.info("isDocument")&&O()},P=function(){s&&T&&h===l&&((A.relSize.width||A.relSize.autoFullWidth)&&i.get.width(window)!=i.get.width(A.spacer.parentNode)||A.relSize.height&&i.get.height(window)!=i.get.height(A.spacer.parentNode))&&_()},D=function(e){s&&T&&h===l&&!s.info("isDocument")&&(e.preventDefault(),s._setScrollPos(s.info("scrollPos")-((e.wheelDelta||e[s.info("vertical")?"wheelDeltaY":"wheelDeltaX"])/3||30*-e.detail)))};this.setPin=function(e,n){var r={pushFollowers:!0,spacerClass:"scrollmagic-pin-spacer"};if(n=i.extend({},r,n),e=i.get.elements(e)[0],!e)return f;if("fixed"===i.css(e,"position"))return f;if(T){if(T===e)return f;f.removePin()}T=e;var o=T.parentNode.style.display,s=["top","left","bottom","right","margin","marginLeft","marginRight","marginTop","marginBottom"];T.parentNode.style.display="none";var a="absolute"!=i.css(T,"position"),l=i.css(T,s.concat(["display"])),c=i.css(T,["width","height"]);T.parentNode.style.display=o,!a&&n.pushFollowers&&(n.pushFollowers=!1);var u=T.parentNode.insertBefore(document.createElement("div"),T),d=i.extend(l,{position:a?"relative":"absolute",boxSizing:"content-box",mozBoxSizing:"content-box",webkitBoxSizing:"content-box"});if(a||i.extend(d,i.css(T,["width","height"])),i.css(u,d),u.setAttribute(t,""),i.addClass(u,n.spacerClass),A={spacer:u,relSize:{width:"%"===c.width.slice(-1),height:"%"===c.height.slice(-1),autoFullWidth:"auto"===c.width&&a&&i.isMarginCollapseType(l.display)},pushFollowers:n.pushFollowers,inFlow:a},!T.___origStyle){T.___origStyle={};var h=T.style,g=s.concat(["width","height","position","boxSizing","mozBoxSizing","webkitBoxSizing"]);g.forEach(function(e){T.___origStyle[e]=h[e]||""})}return A.relSize.width&&i.css(u,{width:c.width}),A.relSize.height&&i.css(u,{height:c.height}),u.appendChild(T),i.css(T,{position:a?"relative":"absolute",margin:"auto",top:"auto",left:"auto",bottom:"auto",right:"auto"}),(A.relSize.width||A.relSize.autoFullWidth)&&i.css(T,{boxSizing:"border-box",mozBoxSizing:"border-box",webkitBoxSizing:"border-box"}),window.addEventListener("scroll",N),window.addEventListener("resize",N),window.addEventListener("resize",P),T.addEventListener("mousewheel",D),T.addEventListener("DOMMouseScroll",D),O(),f},this.removePin=function(e){if(T){if(h===l&&O(!0),e||!s){var n=A.spacer.firstChild;if(n.hasAttribute(t)){var r=A.spacer.style,o=["margin","marginLeft","marginRight","marginTop","marginBottom"];margins={},o.forEach(function(e){margins[e]=r[e]||""}),i.css(n,margins)}A.spacer.parentNode.insertBefore(n,A.spacer),A.spacer.parentNode.removeChild(A.spacer),T.parentNode.hasAttribute(t)||(i.css(T,T.___origStyle),delete T.___origStyle)}window.removeEventListener("scroll",N),window.removeEventListener("resize",N),window.removeEventListener("resize",P),T.removeEventListener("mousewheel",D),T.removeEventListener("DOMMouseScroll",D),T=void 0}return f};var R,k=[];return f.on("destroy.internal",function(e){f.removeClassToggle(e.reset)}),this.setClassToggle=function(e,t){var n=i.get.elements(e);return 0!==n.length&&i.type.String(t)?(k.length>0&&f.removeClassToggle(),R=t,k=n,f.on("enter.internal_class leave.internal_class",function(e){var t="enter"===e.type?i.addClass:i.removeClass;k.forEach(function(e){t(e,R)})}),f):f},this.removeClassToggle=function(e){return e&&k.forEach(function(e){i.removeClass(e,R)}),f.off("start.internal_class end.internal_class"),R=void 0,k=[],f},w(),f};var r={defaults:{duration:0,offset:0,triggerElement:void 0,triggerHook:.5,reverse:!0,loglevel:2},validate:{offset:function(e){if(e=parseFloat(e),!i.type.Number(e))throw 0;return e},triggerElement:function(e){if(e=e||void 0){var t=i.get.elements(e)[0];if(!t)throw 0;e=t}return e},triggerHook:function(e){var t={onCenter:.5,onEnter:1,onLeave:0};if(i.type.Number(e))e=Math.max(0,Math.min(parseFloat(e),1));else{if(!(e in t))throw 0;e=t[e]}return e},reverse:function(e){return!!e}},shifts:["duration","offset","triggerHook"]};e.Scene.addOption=function(e,t,n,i){e in r.defaults||(r.defaults[e]=t,r.validate[e]=n,i&&r.shifts.push(e))},e.Scene.extend=function(t){var n=this;e.Scene=function(){return n.apply(this,arguments),this.$super=i.extend({},this),t.apply(this,arguments)||this},i.extend(e.Scene,n),e.Scene.prototype=n.prototype,e.Scene.prototype.constructor=e.Scene},e.Event=function(e,t,n,r){r=r||{};for(var i in r)this[i]=r[i];return this.type=e,this.target=this.currentTarget=n,this.namespace=t||"",this.timeStamp=this.timestamp=Date.now(),this};var i=e._util=function(e){var t,n={},r=function(e){return parseFloat(e)||0},i=function(t){return t.currentStyle?t.currentStyle:e.getComputedStyle(t)},o=function(t,n,o,s){if(n=n===document?e:n,n===e)s=!1;else if(!f.DomElement(n))return 0;t=t.charAt(0).toUpperCase()+t.substr(1).toLowerCase();var a=(o?n["offset"+t]||n["outer"+t]:n["client"+t]||n["inner"+t])||0;if(o&&s){var l=i(n);a+="Height"===t?r(l.marginTop)+r(l.marginBottom):r(l.marginLeft)+r(l.marginRight)}return a},s=function(e){return e.replace(/^[^a-z]+([a-z])/g,"$1").replace(/-([a-z])/g,function(e){return e[1].toUpperCase()})};n.extend=function(e){for(e=e||{},t=1;t<arguments.length;t++)if(arguments[t])for(var n in arguments[t])arguments[t].hasOwnProperty(n)&&(e[n]=arguments[t][n]);return e},n.isMarginCollapseType=function(e){return["block","flex","list-item","table","-webkit-box"].indexOf(e)>-1};var a=0,l=["ms","moz","webkit","o"],c=e.requestAnimationFrame,u=e.cancelAnimationFrame;for(t=0;!c&&t<l.length;++t)c=e[l[t]+"RequestAnimationFrame"],u=e[l[t]+"CancelAnimationFrame"]||e[l[t]+"CancelRequestAnimationFrame"];c||(c=function(t){var n=(new Date).getTime(),r=Math.max(0,16-(n-a)),i=e.setTimeout(function(){t(n+r)},r);return a=n+r,i}),u||(u=function(t){e.clearTimeout(t)}),n.rAF=c.bind(e),n.cAF=u.bind(e);var f=n.type=function(e){return Object.prototype.toString.call(e).replace(/^\[object (.+)\]$/,"$1").toLowerCase()};f.String=function(e){return"string"===f(e)},f.Function=function(e){return"function"===f(e)},f.Array=function(e){return Array.isArray(e)},f.Number=function(e){return!f.Array(e)&&e-parseFloat(e)+1>=0},f.DomElement=function(e){return"object"==typeof HTMLElement?e instanceof HTMLElement:e&&"object"==typeof e&&null!==e&&1===e.nodeType&&"string"==typeof e.nodeName};var d=n.get={};return d.elements=function(t){var n=[];if(f.String(t))try{t=document.querySelectorAll(t)}catch(r){return n}if("nodelist"===f(t)||f.Array(t))for(var i=0,o=n.length=t.length;o>i;i++){var s=t[i];n[i]=f.DomElement(s)?s:d.elements(s)}else(f.DomElement(t)||t===document||t===e)&&(n=[t]);return n},d.scrollTop=function(t){return t&&"number"==typeof t.scrollTop?t.scrollTop:e.pageYOffset||0},d.scrollLeft=function(t){return t&&"number"==typeof t.scrollLeft?t.scrollLeft:e.pageXOffset||0},d.width=function(e,t,n){return o("width",e,t,n)},d.height=function(e,t,n){return o("height",e,t,n)},d.offset=function(e,t){var n={top:0,left:0};if(e&&e.getBoundingClientRect){var r=e.getBoundingClientRect();n.top=r.top,n.left=r.left,t||(n.top+=d.scrollTop(),n.left+=d.scrollLeft())}return n},n.addClass=function(e,t){t&&(e.classList?e.classList.add(t):e.className+=" "+t)},n.removeClass=function(e,t){t&&(e.classList?e.classList.remove(t):e.className=e.className.replace(RegExp("(^|\\b)"+t.split(" ").join("|")+"(\\b|$)","gi")," "))},n.css=function(e,t){if(f.String(t))return i(e)[s(t)];if(f.Array(t)){var n={},r=i(e);return t.forEach(function(e){n[e]=r[s(e)]}),n}for(var o in t){var a=t[o];a==parseFloat(a)&&(a+="px"),e.style[s(o)]=a}},n}(window||{});return e});
/*! ScrollMagic v2.0.5 | (c) 2015 Jan Paepke (@janpaepke) | license & info: http://scrollmagic.io */

!function(e,i){"function"==typeof define&&define.amd?define(["ScrollMagic","jquery.scrollmagic.min"],i):"object"==typeof exports?i(require("scrollmagic"),require("jquery")):i(e.ScrollMagic,e.jQuery)}(this,function(e, i){"use strict";e._util.get.elements=function(e){return i(e).toArray()},e._util.addClass=function(e, t){i(e).addClass(t)},e._util.removeClass=function(e, t){i(e).removeClass(t)},i.ScrollMagic=e});
;(function () {
		'use strict';

		/**
		 * @preserve FastClick: polyfill to remove click delays on browsers with touch UIs.
		 *
		 * @codingstandard ftlabs-jsv2
		 * @copyright The Financial Times Limited [All Rights Reserved]
		 * @license MIT License (see LICENSE.txt)
		 */

		/*jslint browser:true, node:true*/
		/*global define, Event, Node*/


		/**
		 * Instantiate fast-clicking listeners on the specified layer.
		 *
		 * @constructor
		 * @param {Element} layer The layer to listen on
		 * @param {Object} [options={}] The options to override the defaults
		 */
		function FastClick(layer, options) {
				var oldOnClick;

				options = options || {};

				/**
				 * Whether a click is currently being tracked.
				 *
				 * @type boolean
				 */
				this.trackingClick = false;


				/**
				 * Timestamp for when click tracking started.
				 *
				 * @type number
				 */
				this.trackingClickStart = 0;


				/**
				 * The element being tracked for a click.
				 *
				 * @type EventTarget
				 */
				this.targetElement = null;


				/**
				 * X-coordinate of touch start event.
				 *
				 * @type number
				 */
				this.touchStartX = 0;


				/**
				 * Y-coordinate of touch start event.
				 *
				 * @type number
				 */
				this.touchStartY = 0;


				/**
				 * ID of the last touch, retrieved from Touch.identifier.
				 *
				 * @type number
				 */
				this.lastTouchIdentifier = 0;


				/**
				 * Touchmove boundary, beyond which a click will be cancelled.
				 *
				 * @type number
				 */
				this.touchBoundary = options.touchBoundary || 10;


				/**
				 * The FastClick layer.
				 *
				 * @type Element
				 */
				this.layer = layer;

				/**
				 * The minimum time between tap(touchstart and touchend) events
				 *
				 * @type number
				 */
				this.tapDelay = options.tapDelay || 200;

				/**
				 * The maximum time for a tap
				 *
				 * @type number
				 */
				this.tapTimeout = options.tapTimeout || 700;

				if (FastClick.notNeeded(layer)) {
						return;
				}

				// Some old versions of Android don't have Function.prototype.bind
				function bind(method, context) {
						return function() { return method.apply(context, arguments); };
				}


				var methods = ['onMouse', 'onClick', 'onTouchStart', 'onTouchMove', 'onTouchEnd', 'onTouchCancel'];
				var context = this;
				for (var i = 0, l = methods.length; i < l; i++) {
						context[methods[i]] = bind(context[methods[i]], context);
				}

				// Set up event handlers as required
				if (deviceIsAndroid) {
						layer.addEventListener('mouseover', this.onMouse, true);
						layer.addEventListener('mousedown', this.onMouse, true);
						layer.addEventListener('mouseup', this.onMouse, true);
				}

				layer.addEventListener('click', this.onClick, true);
				layer.addEventListener('touchstart', this.onTouchStart, false);
				layer.addEventListener('touchmove', this.onTouchMove, false);
				layer.addEventListener('touchend', this.onTouchEnd, false);
				layer.addEventListener('touchcancel', this.onTouchCancel, false);

				// Hack is required for browsers that don't support Event#stopImmediatePropagation (e.g. Android 2)
				// which is how FastClick normally stops click events bubbling to callbacks registered on the FastClick
				// layer when they are cancelled.
				if (!Event.prototype.stopImmediatePropagation) {
						layer.removeEventListener = function(type, callback, capture) {
								var rmv = Node.prototype.removeEventListener;
								if (type === 'click') {
										rmv.call(layer, type, callback.hijacked || callback, capture);
								} else {
										rmv.call(layer, type, callback, capture);
								}
						};

						layer.addEventListener = function(type, callback, capture) {
								var adv = Node.prototype.addEventListener;
								if (type === 'click') {
										adv.call(layer, type, callback.hijacked || (callback.hijacked = function(event) {
														if (!event.propagationStopped) {
																callback(event);
														}
												}), capture);
								} else {
										adv.call(layer, type, callback, capture);
								}
						};
				}

				// If a handler is already declared in the element's onclick attribute, it will be fired before
				// FastClick's onClick handler. Fix this by pulling out the user-defined handler function and
				// adding it as listener.
				if (typeof layer.onclick === 'function') {

						// Android browser on at least 3.2 requires a new reference to the function in layer.onclick
						// - the old one won't work if passed to addEventListener directly.
						oldOnClick = layer.onclick;
						layer.addEventListener('click', function(event) {
								oldOnClick(event);
						}, false);
						layer.onclick = null;
				}
		}

		/**
		 * Windows Phone 8.1 fakes user agent string to look like Android and iPhone.
		 *
		 * @type boolean
		 */
		var deviceIsWindowsPhone = navigator.userAgent.indexOf("Windows Phone") >= 0;

		/**
		 * Android requires exceptions.
		 *
		 * @type boolean
		 */
		var deviceIsAndroid = navigator.userAgent.indexOf('Android') > 0 && !deviceIsWindowsPhone;


		/**
		 * iOS requires exceptions.
		 *
		 * @type boolean
		 */
		var deviceIsIOS = /iP(ad|hone|od)/.test(navigator.userAgent) && !deviceIsWindowsPhone;


		/**
		 * iOS 4 requires an exception for select elements.
		 *
		 * @type boolean
		 */
		var deviceIsIOS4 = deviceIsIOS && (/OS 4_\d(_\d)?/).test(navigator.userAgent);


		/**
		 * iOS 6.0-7.* requires the target element to be manually derived
		 *
		 * @type boolean
		 */
		var deviceIsIOSWithBadTarget = deviceIsIOS && (/OS [6-7]_\d/).test(navigator.userAgent);

		/**
		 * BlackBerry requires exceptions.
		 *
		 * @type boolean
		 */
		var deviceIsBlackBerry10 = navigator.userAgent.indexOf('BB10') > 0;

		/**
		 * Determine whether a given element requires a native click.
		 *
		 * @param {EventTarget|Element} target Target DOM element
		 * @returns {boolean} Returns true if the element needs a native click
		 */
		FastClick.prototype.needsClick = function(target) {
				switch (target.nodeName.toLowerCase()) {

						// Don't send a synthetic click to disabled inputs (issue #62)
						case 'button':
						case 'select':
						case 'textarea':
								if (target.disabled) {
										return true;
								}

								break;
						case 'input':

								// File inputs need real clicks on iOS 6 due to a browser bug (issue #68)
								if ((deviceIsIOS && target.type === 'file') || target.disabled) {
										return true;
								}

								break;
						case 'label':
						case 'iframe': // iOS8 homescreen apps can prevent events bubbling into frames
						case 'video':
								return true;
				}

				return (/\bneedsclick\b/).test(target.className);
		};


		/**
		 * Determine whether a given element requires a call to focus to simulate click into element.
		 *
		 * @param {EventTarget|Element} target Target DOM element
		 * @returns {boolean} Returns true if the element requires a call to focus to simulate native click.
		 */
		FastClick.prototype.needsFocus = function(target) {
				switch (target.nodeName.toLowerCase()) {
						case 'textarea':
								return true;
						case 'select':
								return !deviceIsAndroid;
						case 'input':
								switch (target.type) {
										case 'button':
										case 'checkbox':
										case 'file':
										case 'image':
										case 'radio':
										case 'submit':
												return false;
								}

								// No point in attempting to focus disabled inputs
								return !target.disabled && !target.readOnly;
						default:
								return (/\bneedsfocus\b/).test(target.className);
				}
		};


		/**
		 * Send a click event to the specified element.
		 *
		 * @param {EventTarget|Element} targetElement
		 * @param {Event} event
		 */
		FastClick.prototype.sendClick = function(targetElement, event) {
				var clickEvent, touch;

				// On some Android devices activeElement needs to be blurred otherwise the synthetic click will have no effect (#24)
				if (document.activeElement && document.activeElement !== targetElement) {
						document.activeElement.blur();
				}

				touch = event.changedTouches[0];

				// Synthesise a click event, with an extra attribute so it can be tracked
				clickEvent = document.createEvent('MouseEvents');
				clickEvent.initMouseEvent(this.determineEventType(targetElement), true, true, window, 1, touch.screenX, touch.screenY, touch.clientX, touch.clientY, false, false, false, false, 0, null);
				clickEvent.forwardedTouchEvent = true;
				targetElement.dispatchEvent(clickEvent);
		};

		FastClick.prototype.determineEventType = function(targetElement) {

				//Issue #159: Android Chrome Select Box does not open with a synthetic click event
				if (deviceIsAndroid && targetElement.tagName.toLowerCase() === 'select') {
						return 'mousedown';
				}

				return 'click';
		};


		/**
		 * @param {EventTarget|Element} targetElement
		 */
		FastClick.prototype.focus = function(targetElement) {
				var length;

				// Issue #160: on iOS 7, some input elements (e.g. date datetime month) throw a vague TypeError on setSelectionRange. These elements don't have an integer value for the selectionStart and selectionEnd properties, but unfortunately that can't be used for detection because accessing the properties also throws a TypeError. Just check the type instead. Filed as Apple bug #15122724.
				if (deviceIsIOS && targetElement.setSelectionRange && targetElement.type.indexOf('date') !== 0 && targetElement.type !== 'time' && targetElement.type !== 'month') {
						length = targetElement.value.length;
						targetElement.setSelectionRange(length, length);
				} else {
						targetElement.focus();
				}
		};


		/**
		 * Check whether the given target element is a child of a scrollable layer and if so, set a flag on it.
		 *
		 * @param {EventTarget|Element} targetElement
		 */
		FastClick.prototype.updateScrollParent = function(targetElement) {
				var scrollParent, parentElement;

				scrollParent = targetElement.fastClickScrollParent;

				// Attempt to discover whether the target element is contained within a scrollable layer. Re-check if the
				// target element was moved to another parent.
				if (!scrollParent || !scrollParent.contains(targetElement)) {
						parentElement = targetElement;
						do {
								if (parentElement.scrollHeight > parentElement.offsetHeight) {
										scrollParent = parentElement;
										targetElement.fastClickScrollParent = parentElement;
										break;
								}

								parentElement = parentElement.parentElement;
						} while (parentElement);
				}

				// Always update the scroll top tracker if possible.
				if (scrollParent) {
						scrollParent.fastClickLastScrollTop = scrollParent.scrollTop;
				}
		};


		/**
		 * @param {EventTarget} targetElement
		 * @returns {Element|EventTarget}
		 */
		FastClick.prototype.getTargetElementFromEventTarget = function(eventTarget) {

				// On some older browsers (notably Safari on iOS 4.1 - see issue #56) the event target may be a text node.
				if (eventTarget.nodeType === Node.TEXT_NODE) {
						return eventTarget.parentNode;
				}

				return eventTarget;
		};


		/**
		 * On touch start, record the position and scroll offset.
		 *
		 * @param {Event} event
		 * @returns {boolean}
		 */
		FastClick.prototype.onTouchStart = function(event) {
				var targetElement, touch, selection;

				// Ignore multiple touches, otherwise pinch-to-zoom is prevented if both fingers are on the FastClick element (issue #111).
				if (event.targetTouches.length > 1) {
						return true;
				}

				targetElement = this.getTargetElementFromEventTarget(event.target);
				touch = event.targetTouches[0];

				if (deviceIsIOS) {

						// Only trusted events will deselect text on iOS (issue #49)
						selection = window.getSelection();
						if (selection.rangeCount && !selection.isCollapsed) {
								return true;
						}

						if (!deviceIsIOS4) {

								// Weird things happen on iOS when an alert or confirm dialog is opened from a click event callback (issue #23):
								// when the user next taps anywhere else on the page, new touchstart and touchend events are dispatched
								// with the same identifier as the touch event that previously triggered the click that triggered the alert.
								// Sadly, there is an issue on iOS 4 that causes some normal touch events to have the same identifier as an
								// immediately preceeding touch event (issue #52), so this fix is unavailable on that platform.
								// Issue 120: touch.identifier is 0 when Chrome dev tools 'Emulate touch events' is set with an iOS device UA string,
								// which causes all touch events to be ignored. As this block only applies to iOS, and iOS identifiers are always long,
								// random integers, it's safe to to continue if the identifier is 0 here.
								if (touch.identifier && touch.identifier === this.lastTouchIdentifier) {
										event.preventDefault();
										return false;
								}

								this.lastTouchIdentifier = touch.identifier;

								// If the target element is a child of a scrollable layer (using -webkit-overflow-scrolling: touch) and:
								// 1) the user does a fling scroll on the scrollable layer
								// 2) the user stops the fling scroll with another tap
								// then the event.target of the last 'touchend' event will be the element that was under the user's finger
								// when the fling scroll was started, causing FastClick to send a click event to that layer - unless a check
								// is made to ensure that a parent layer was not scrolled before sending a synthetic click (issue #42).
								this.updateScrollParent(targetElement);
						}
				}

				this.trackingClick = true;
				this.trackingClickStart = event.timeStamp;
				this.targetElement = targetElement;

				this.touchStartX = touch.pageX;
				this.touchStartY = touch.pageY;

				// Prevent phantom clicks on fast double-tap (issue #36)
				if ((event.timeStamp - this.lastClickTime) < this.tapDelay) {
						event.preventDefault();
				}

				return true;
		};


		/**
		 * Based on a touchmove event object, check whether the touch has moved past a boundary since it started.
		 *
		 * @param {Event} event
		 * @returns {boolean}
		 */
		FastClick.prototype.touchHasMoved = function(event) {
				var touch = event.changedTouches[0], boundary = this.touchBoundary;

				if (Math.abs(touch.pageX - this.touchStartX) > boundary || Math.abs(touch.pageY - this.touchStartY) > boundary) {
						return true;
				}

				return false;
		};


		/**
		 * Update the last position.
		 *
		 * @param {Event} event
		 * @returns {boolean}
		 */
		FastClick.prototype.onTouchMove = function(event) {
				if (!this.trackingClick) {
						return true;
				}

				// If the touch has moved, cancel the click tracking
				if (this.targetElement !== this.getTargetElementFromEventTarget(event.target) || this.touchHasMoved(event)) {
						this.trackingClick = false;
						this.targetElement = null;
				}

				return true;
		};


		/**
		 * Attempt to find the labelled control for the given label element.
		 *
		 * @param {EventTarget|HTMLLabelElement} labelElement
		 * @returns {Element|null}
		 */
		FastClick.prototype.findControl = function(labelElement) {

				// Fast path for newer browsers supporting the HTML5 control attribute
				if (labelElement.control !== undefined) {
						return labelElement.control;
				}

				// All browsers under test that support touch events also support the HTML5 htmlFor attribute
				if (labelElement.htmlFor) {
						return document.getElementById(labelElement.htmlFor);
				}

				// If no for attribute exists, attempt to retrieve the first labellable descendant element
				// the list of which is defined here: http://www.w3.org/TR/html5/forms.html#category-label
				return labelElement.querySelector('button, input:not([type=hidden]), keygen, meter, output, progress, select, textarea');
		};


		/**
		 * On touch end, determine whether to send a click event at once.
		 *
		 * @param {Event} event
		 * @returns {boolean}
		 */
		FastClick.prototype.onTouchEnd = function(event) {
				var forElement, trackingClickStart, targetTagName, scrollParent, touch, targetElement = this.targetElement;

				if (!this.trackingClick) {
						return true;
				}

				// Prevent phantom clicks on fast double-tap (issue #36)
				if ((event.timeStamp - this.lastClickTime) < this.tapDelay) {
						this.cancelNextClick = true;
						return true;
				}

				if ((event.timeStamp - this.trackingClickStart) > this.tapTimeout) {
						return true;
				}

				// Reset to prevent wrong click cancel on input (issue #156).
				this.cancelNextClick = false;

				this.lastClickTime = event.timeStamp;

				trackingClickStart = this.trackingClickStart;
				this.trackingClick = false;
				this.trackingClickStart = 0;

				// On some iOS devices, the targetElement supplied with the event is invalid if the layer
				// is performing a transition or scroll, and has to be re-detected manually. Note that
				// for this to function correctly, it must be called *after* the event target is checked!
				// See issue #57; also filed as rdar://13048589 .
				if (deviceIsIOSWithBadTarget) {
						touch = event.changedTouches[0];

						// In certain cases arguments of elementFromPoint can be negative, so prevent setting targetElement to null
						targetElement = document.elementFromPoint(touch.pageX - window.pageXOffset, touch.pageY - window.pageYOffset) || targetElement;
						targetElement.fastClickScrollParent = this.targetElement.fastClickScrollParent;
				}

				targetTagName = targetElement.tagName.toLowerCase();
				if (targetTagName === 'label') {
						forElement = this.findControl(targetElement);
						if (forElement) {
								this.focus(targetElement);
								if (deviceIsAndroid) {
										return false;
								}

								targetElement = forElement;
						}
				} else if (this.needsFocus(targetElement)) {

						// Case 1: If the touch started a while ago (best guess is 100ms based on tests for issue #36) then focus will be triggered anyway. Return early and unset the target element reference so that the subsequent click will be allowed through.
						// Case 2: Without this exception for input elements tapped when the document is contained in an iframe, then any inputted text won't be visible even though the value attribute is updated as the user types (issue #37).
						if ((event.timeStamp - trackingClickStart) > 100 || (deviceIsIOS && window.top !== window && targetTagName === 'input')) {
								this.targetElement = null;
								return false;
						}

						this.focus(targetElement);
						this.sendClick(targetElement, event);

						// Select elements need the event to go through on iOS 4, otherwise the selector menu won't open.
						// Also this breaks opening selects when VoiceOver is active on iOS6, iOS7 (and possibly others)
						if (!deviceIsIOS || targetTagName !== 'select') {
								this.targetElement = null;
								event.preventDefault();
						}

						return false;
				}

				if (deviceIsIOS && !deviceIsIOS4) {

						// Don't send a synthetic click event if the target element is contained within a parent layer that was scrolled
						// and this tap is being used to stop the scrolling (usually initiated by a fling - issue #42).
						scrollParent = targetElement.fastClickScrollParent;
						if (scrollParent && scrollParent.fastClickLastScrollTop !== scrollParent.scrollTop) {
								return true;
						}
				}

				// Prevent the actual click from going though - unless the target node is marked as requiring
				// real clicks or if it is in the whitelist in which case only non-programmatic clicks are permitted.
				if (!this.needsClick(targetElement)) {
						event.preventDefault();
						this.sendClick(targetElement, event);
				}

				return false;
		};


		/**
		 * On touch cancel, stop tracking the click.
		 *
		 * @returns {void}
		 */
		FastClick.prototype.onTouchCancel = function() {
				this.trackingClick = false;
				this.targetElement = null;
		};


		/**
		 * Determine mouse events which should be permitted.
		 *
		 * @param {Event} event
		 * @returns {boolean}
		 */
		FastClick.prototype.onMouse = function(event) {

				// If a target element was never set (because a touch event was never fired) allow the event
				if (!this.targetElement) {
						return true;
				}

				if (event.forwardedTouchEvent) {
						return true;
				}

				// Programmatically generated events targeting a specific element should be permitted
				if (!event.cancelable) {
						return true;
				}

				// Derive and check the target element to see whether the mouse event needs to be permitted;
				// unless explicitly enabled, prevent non-touch click events from triggering actions,
				// to prevent ghost/doubleclicks.
				if (!this.needsClick(this.targetElement) || this.cancelNextClick) {

						// Prevent any user-added listeners declared on FastClick element from being fired.
						if (event.stopImmediatePropagation) {
								event.stopImmediatePropagation();
						} else {

								// Part of the hack for browsers that don't support Event#stopImmediatePropagation (e.g. Android 2)
								event.propagationStopped = true;
						}

						// Cancel the event
						event.stopPropagation();
						event.preventDefault();

						return false;
				}

				// If the mouse event is permitted, return true for the action to go through.
				return true;
		};


		/**
		 * On actual clicks, determine whether this is a touch-generated click, a click action occurring
		 * naturally after a delay after a touch (which needs to be cancelled to avoid duplication), or
		 * an actual click which should be permitted.
		 *
		 * @param {Event} event
		 * @returns {boolean}
		 */
		FastClick.prototype.onClick = function(event) {
				var permitted;

				// It's possible for another FastClick-like library delivered with third-party code to fire a click event before FastClick does (issue #44). In that case, set the click-tracking flag back to false and return early. This will cause onTouchEnd to return early.
				if (this.trackingClick) {
						this.targetElement = null;
						this.trackingClick = false;
						return true;
				}

				// Very odd behaviour on iOS (issue #18): if a submit element is present inside a form and the user hits enter in the iOS simulator or clicks the Go button on the pop-up OS keyboard the a kind of 'fake' click event will be triggered with the submit-type input element as the target.
				if (event.target.type === 'submit' && event.detail === 0) {
						return true;
				}

				permitted = this.onMouse(event);

				// Only unset targetElement if the click is not permitted. This will ensure that the check for !targetElement in onMouse fails and the browser's click doesn't go through.
				if (!permitted) {
						this.targetElement = null;
				}

				// If clicks are permitted, return true for the action to go through.
				return permitted;
		};


		/**
		 * Remove all FastClick's event listeners.
		 *
		 * @returns {void}
		 */
		FastClick.prototype.destroy = function() {
				var layer = this.layer;

				if (deviceIsAndroid) {
						layer.removeEventListener('mouseover', this.onMouse, true);
						layer.removeEventListener('mousedown', this.onMouse, true);
						layer.removeEventListener('mouseup', this.onMouse, true);
				}

				layer.removeEventListener('click', this.onClick, true);
				layer.removeEventListener('touchstart', this.onTouchStart, false);
				layer.removeEventListener('touchmove', this.onTouchMove, false);
				layer.removeEventListener('touchend', this.onTouchEnd, false);
				layer.removeEventListener('touchcancel', this.onTouchCancel, false);
		};


		/**
		 * Check whether FastClick is needed.
		 *
		 * @param {Element} layer The layer to listen on
		 */
		FastClick.notNeeded = function(layer) {
				var metaViewport;
				var chromeVersion;
				var blackberryVersion;
				var firefoxVersion;

				// Devices that don't support touch don't need FastClick
				if (typeof window.ontouchstart === 'undefined') {
						return true;
				}

				// Chrome version - zero for other browsers
				chromeVersion = +(/Chrome\/([0-9]+)/.exec(navigator.userAgent) || [,0])[1];

				if (chromeVersion) {

						if (deviceIsAndroid) {
								metaViewport = document.querySelector('meta[name=viewport]');

								if (metaViewport) {
										// Chrome on Android with user-scalable="no" doesn't need FastClick (issue #89)
										if (metaViewport.content.indexOf('user-scalable=no') !== -1) {
												return true;
										}
										// Chrome 32 and above with width=device-width or less don't need FastClick
										if (chromeVersion > 31 && document.documentElement.scrollWidth <= window.outerWidth) {
												return true;
										}
								}

								// Chrome desktop doesn't need FastClick (issue #15)
						} else {
								return true;
						}
				}

				if (deviceIsBlackBerry10) {
						blackberryVersion = navigator.userAgent.match(/Version\/([0-9]*)\.([0-9]*)/);

						// BlackBerry 10.3+ does not require Fastclick library.
						// https://github.com/ftlabs/fastclick/issues/251
						if (blackberryVersion[1] >= 10 && blackberryVersion[2] >= 3) {
								metaViewport = document.querySelector('meta[name=viewport]');

								if (metaViewport) {
										// user-scalable=no eliminates click delay.
										if (metaViewport.content.indexOf('user-scalable=no') !== -1) {
												return true;
										}
										// width=device-width (or less than device-width) eliminates click delay.
										if (document.documentElement.scrollWidth <= window.outerWidth) {
												return true;
										}
								}
						}
				}

				// IE10 with -ms-touch-action: none or manipulation, which disables double-tap-to-zoom (issue #97)
				if (layer.style.msTouchAction === 'none' || layer.style.touchAction === 'manipulation') {
						return true;
				}

				// Firefox version - zero for other browsers
				firefoxVersion = +(/Firefox\/([0-9]+)/.exec(navigator.userAgent) || [,0])[1];

				if (firefoxVersion >= 27) {
						// Firefox 27+ does not have tap delay if the content is not zoomable - https://bugzilla.mozilla.org/show_bug.cgi?id=922896

						metaViewport = document.querySelector('meta[name=viewport]');
						if (metaViewport && (metaViewport.content.indexOf('user-scalable=no') !== -1 || document.documentElement.scrollWidth <= window.outerWidth)) {
								return true;
						}
				}

				// IE11: prefixed -ms-touch-action is no longer supported and it's recomended to use non-prefixed version
				// http://msdn.microsoft.com/en-us/library/windows/apps/Hh767313.aspx
				if (layer.style.touchAction === 'none' || layer.style.touchAction === 'manipulation') {
						return true;
				}

				return false;
		};


		/**
		 * Factory method for creating a FastClick object
		 *
		 * @param {Element} layer The layer to listen on
		 * @param {Object} [options={}] The options to override the defaults
		 */
		FastClick.attach = function(layer, options) {
				return new FastClick(layer, options);
		};


		if (typeof define === 'function' && typeof define.amd === 'object' && define.amd) {

				// AMD. Register as an anonymous module.
				define(function() {
						return FastClick;
				});
		} else if (typeof module !== 'undefined' && module.exports) {
				module.exports = FastClick.attach;
				module.exports.FastClick = FastClick;
		} else {
				window.FastClick = FastClick;
		}
}());

/*!
 * imagesLoaded PACKAGED v3.1.8
 * JavaScript is all like "You images are done yet or what?"
 * MIT License
 */

(function(){function e(){}function t(e,t){for(var n=e.length;n--;)if(e[n].listener===t)return n;return-1}function n(e){return function(){return this[e].apply(this,arguments)}}var i=e.prototype,r=this,o=r.EventEmitter;i.getListeners=function(e){var t,n,i=this._getEvents();if("object"==typeof e){t={};for(n in i)i.hasOwnProperty(n)&&e.test(n)&&(t[n]=i[n])}else t=i[e]||(i[e]=[]);return t},i.flattenListeners=function(e){var t,n=[];for(t=0;e.length>t;t+=1)n.push(e[t].listener);return n},i.getListenersAsObject=function(e){var t,n=this.getListeners(e);return n instanceof Array&&(t={},t[e]=n),t||n},i.addListener=function(e,n){var i,r=this.getListenersAsObject(e),o="object"==typeof n;for(i in r)r.hasOwnProperty(i)&&-1===t(r[i],n)&&r[i].push(o?n:{listener:n,once:!1});return this},i.on=n("addListener"),i.addOnceListener=function(e,t){return this.addListener(e,{listener:t,once:!0})},i.once=n("addOnceListener"),i.defineEvent=function(e){return this.getListeners(e),this},i.defineEvents=function(e){for(var t=0;e.length>t;t+=1)this.defineEvent(e[t]);return this},i.removeListener=function(e,n){var i,r,o=this.getListenersAsObject(e);for(r in o)o.hasOwnProperty(r)&&(i=t(o[r],n),-1!==i&&o[r].splice(i,1));return this},i.off=n("removeListener"),i.addListeners=function(e,t){return this.manipulateListeners(!1,e,t)},i.removeListeners=function(e,t){return this.manipulateListeners(!0,e,t)},i.manipulateListeners=function(e,t,n){var i,r,o=e?this.removeListener:this.addListener,s=e?this.removeListeners:this.addListeners;if("object"!=typeof t||t instanceof RegExp)for(i=n.length;i--;)o.call(this,t,n[i]);else for(i in t)t.hasOwnProperty(i)&&(r=t[i])&&("function"==typeof r?o.call(this,i,r):s.call(this,i,r));return this},i.removeEvent=function(e){var t,n=typeof e,i=this._getEvents();if("string"===n)delete i[e];else if("object"===n)for(t in i)i.hasOwnProperty(t)&&e.test(t)&&delete i[t];else delete this._events;return this},i.removeAllListeners=n("removeEvent"),i.emitEvent=function(e,t){var n,i,r,o,s=this.getListenersAsObject(e);for(r in s)if(s.hasOwnProperty(r))for(i=s[r].length;i--;)n=s[r][i],n.once===!0&&this.removeListener(e,n.listener),o=n.listener.apply(this,t||[]),o===this._getOnceReturnValue()&&this.removeListener(e,n.listener);return this},i.trigger=n("emitEvent"),i.emit=function(e){var t=Array.prototype.slice.call(arguments,1);return this.emitEvent(e,t)},i.setOnceReturnValue=function(e){return this._onceReturnValue=e,this},i._getOnceReturnValue=function(){return this.hasOwnProperty("_onceReturnValue")?this._onceReturnValue:!0},i._getEvents=function(){return this._events||(this._events={})},e.noConflict=function(){return r.EventEmitter=o,e},"function"==typeof define&&define.amd?define("eventEmitter/EventEmitter",[],function(){return e}):"object"==typeof module&&module.exports?module.exports=e:this.EventEmitter=e}).call(this),function(e){function t(t){var n=e.event;return n.target=n.target||n.srcElement||t,n}var n=document.documentElement,i=function(){};n.addEventListener?i=function(e,t,n){e.addEventListener(t,n,!1)}:n.attachEvent&&(i=function(e,n,i){e[n+i]=i.handleEvent?function(){var n=t(e);i.handleEvent.call(i,n)}:function(){var n=t(e);i.call(e,n)},e.attachEvent("on"+n,e[n+i])});var r=function(){};n.removeEventListener?r=function(e,t,n){e.removeEventListener(t,n,!1)}:n.detachEvent&&(r=function(e,t,n){e.detachEvent("on"+t,e[t+n]);try{delete e[t+n]}catch(i){e[t+n]=void 0}});var o={bind:i,unbind:r};"function"==typeof define&&define.amd?define("eventie/eventie",o):e.eventie=o}(this),function(e,t){"function"==typeof define&&define.amd?define(["eventEmitter/EventEmitter","eventie/eventie"],function(n,i){return t(e,n,i)}):"object"==typeof exports?module.exports=t(e,require("wolfy87-eventemitter"),require("eventie")):e.imagesLoaded=t(e,e.EventEmitter,e.eventie)}(window,function(e,t,n){function i(e,t){for(var n in t)e[n]=t[n];return e}function r(e){return"[object Array]"===d.call(e)}function o(e){var t=[];if(r(e))t=e;else if("number"==typeof e.length)for(var n=0,i=e.length;i>n;n++)t.push(e[n]);else t.push(e);return t}function s(e,t,n){if(!(this instanceof s))return new s(e,t);"string"==typeof e&&(e=document.querySelectorAll(e)),this.elements=o(e),this.options=i({},this.options),"function"==typeof t?n=t:i(this.options,t),n&&this.on("always",n),this.getImages(),a&&(this.jqDeferred=new a.Deferred);var r=this;setTimeout(function(){r.check()})}function f(e){this.img=e}function c(e){this.src=e,v[e]=this}var a=e.jQuery,u=e.console,h=u!==void 0,d=Object.prototype.toString;s.prototype=new t,s.prototype.options={},s.prototype.getImages=function(){this.images=[];for(var e=0,t=this.elements.length;t>e;e++){var n=this.elements[e];"IMG"===n.nodeName&&this.addImage(n);var i=n.nodeType;if(i&&(1===i||9===i||11===i))for(var r=n.querySelectorAll("img"),o=0,s=r.length;s>o;o++){var f=r[o];this.addImage(f)}}},s.prototype.addImage=function(e){var t=new f(e);this.images.push(t)},s.prototype.check=function(){function e(e,r){return t.options.debug&&h&&u.log("confirm",e,r),t.progress(e),n++,n===i&&t.complete(),!0}var t=this,n=0,i=this.images.length;if(this.hasAnyBroken=!1,!i)return this.complete(),void 0;for(var r=0;i>r;r++){var o=this.images[r];o.on("confirm",e),o.check()}},s.prototype.progress=function(e){this.hasAnyBroken=this.hasAnyBroken||!e.isLoaded;var t=this;setTimeout(function(){t.emit("progress",t,e),t.jqDeferred&&t.jqDeferred.notify&&t.jqDeferred.notify(t,e)})},s.prototype.complete=function(){var e=this.hasAnyBroken?"fail":"done";this.isComplete=!0;var t=this;setTimeout(function(){if(t.emit(e,t),t.emit("always",t),t.jqDeferred){var n=t.hasAnyBroken?"reject":"resolve";t.jqDeferred[n](t)}})},a&&(a.fn.imagesLoaded=function(e,t){var n=new s(this,e,t);return n.jqDeferred.promise(a(this))}),f.prototype=new t,f.prototype.check=function(){var e=v[this.img.src]||new c(this.img.src);if(e.isConfirmed)return this.confirm(e.isLoaded,"cached was confirmed"),void 0;if(this.img.complete&&void 0!==this.img.naturalWidth)return this.confirm(0!==this.img.naturalWidth,"naturalWidth"),void 0;var t=this;e.on("confirm",function(e,n){return t.confirm(e.isLoaded,n),!0}),e.check()},f.prototype.confirm=function(e,t){this.isLoaded=e,this.emit("confirm",this,t)};var v={};return c.prototype=new t,c.prototype.check=function(){if(!this.isChecked){var e=new Image;n.bind(e,"load",this),n.bind(e,"error",this),e.src=this.src,this.isChecked=!0}},c.prototype.handleEvent=function(e){var t="on"+e.type;this[t]&&this[t](e)},c.prototype.onload=function(e){this.confirm(!0,"onload"),this.unbindProxyEvents(e)},c.prototype.onerror=function(e){this.confirm(!1,"onerror"),this.unbindProxyEvents(e)},c.prototype.confirm=function(e,t){this.isConfirmed=!0,this.isLoaded=e,this.emit("confirm",this,t)},c.prototype.unbindProxyEvents=function(e){n.unbind(e.target,"load",this),n.unbind(e.target,"error",this)},s});
/*
 * jquery-match-height 0.7.0 by @liabru
 * http://brm.io/jquery-match-height/
 * License MIT
 */
!function(t){"use strict";"function"==typeof define&&define.amd?define(["jquery"],t):"undefined"!=typeof module&&module.exports?module.exports=t(require("jquery")):t(jQuery)}(function(t){var e=-1,o=-1,i=function(t){return parseFloat(t)||0},a=function(e){var o=1,a=t(e),n=null,r=[];return a.each(function(){var e=t(this),a=e.offset().top-i(e.css("margin-top")),s=r.length>0?r[r.length-1]:null;null===s?r.push(e):Math.floor(Math.abs(n-a))<=o?r[r.length-1]=s.add(e):r.push(e),n=a}),r},n=function(e){var o={
		byRow:!0,property:"height",target:null,remove:!1};return"object"==typeof e?t.extend(o,e):("boolean"==typeof e?o.byRow=e:"remove"===e&&(o.remove=!0),o)},r=t.fn.matchHeight=function(e){var o=n(e);if(o.remove){var i=this;return this.css(o.property,""),t.each(r._groups,function(t,e){e.elements=e.elements.not(i)}),this}return this.length<=1&&!o.target?this:(r._groups.push({elements:this,options:o}),r._apply(this,o),this)};r.version="0.7.0",r._groups=[],r._throttle=80,r._maintainScroll=!1,r._beforeUpdate=null,
		r._afterUpdate=null,r._rows=a,r._parse=i,r._parseOptions=n,r._apply=function(e,o){var s=n(o),h=t(e),l=[h],c=t(window).scrollTop(),p=t("html").outerHeight(!0),d=h.parents().filter(":hidden");return d.each(function(){var e=t(this);e.data("style-cache",e.attr("style"))}),d.css("display","block"),s.byRow&&!s.target&&(h.each(function(){var e=t(this),o=e.css("display");"inline-block"!==o&&"flex"!==o&&"inline-flex"!==o&&(o="block"),e.data("style-cache",e.attr("style")),e.css({display:o,"padding-top":"0",
		"padding-bottom":"0","margin-top":"0","margin-bottom":"0","border-top-width":"0","border-bottom-width":"0",height:"100px",overflow:"hidden"})}),l=a(h),h.each(function(){var e=t(this);e.attr("style",e.data("style-cache")||"")})),t.each(l,function(e,o){var a=t(o),n=0;if(s.target)n=s.target.outerHeight(!1);else{if(s.byRow&&a.length<=1)return void a.css(s.property,"");a.each(function(){var e=t(this),o=e.attr("style"),i=e.css("display");"inline-block"!==i&&"flex"!==i&&"inline-flex"!==i&&(i="block");var a={
		display:i};a[s.property]="",e.css(a),e.outerHeight(!1)>n&&(n=e.outerHeight(!1)),o?e.attr("style",o):e.css("display","")})}a.each(function(){var e=t(this),o=0;s.target&&e.is(s.target)||("border-box"!==e.css("box-sizing")&&(o+=i(e.css("border-top-width"))+i(e.css("border-bottom-width")),o+=i(e.css("padding-top"))+i(e.css("padding-bottom"))),e.css(s.property,n-o+"px"))})}),d.each(function(){var e=t(this);e.attr("style",e.data("style-cache")||null)}),r._maintainScroll&&t(window).scrollTop(c/p*t("html").outerHeight(!0)),
		this},r._applyDataApi=function(){var e={};t("[data-match-height], [data-mh]").each(function(){var o=t(this),i=o.attr("data-mh")||o.attr("data-match-height");i in e?e[i]=e[i].add(o):e[i]=o}),t.each(e,function(){this.matchHeight(!0)})};var s=function(e){r._beforeUpdate&&r._beforeUpdate(e,r._groups),t.each(r._groups,function(){r._apply(this.elements,this.options)}),r._afterUpdate&&r._afterUpdate(e,r._groups)};r._update=function(i,a){if(a&&"resize"===a.type){var n=t(window).width();if(n===e)return;e=n;
}i?-1===o&&(o=setTimeout(function(){s(a),o=-1},r._throttle)):s(a)},t(r._applyDataApi),t(window).bind("load",function(t){r._update(!1,t)}),t(window).bind("resize orientationchange",function(t){r._update(!0,t)})});
/*! Copyright (c) 2013 Brandon Aaron (http://brandon.aaron.sh)
 * Licensed under the MIT License (LICENSE.txt).
 *
 * Version: 3.1.12
 *
 * Requires: jQuery 1.2.2+
 */
!function(a){"function"==typeof define&&define.amd?define(["jquery"],a):"object"==typeof exports?module.exports=a:a(jQuery)}(function(a){function b(b){var g=b||window.event,h=i.call(arguments,1),j=0,l=0,m=0,n=0,o=0,p=0;if(b=a.event.fix(g),b.type="mousewheel","detail"in g&&(m=-1*g.detail),"wheelDelta"in g&&(m=g.wheelDelta),"wheelDeltaY"in g&&(m=g.wheelDeltaY),"wheelDeltaX"in g&&(l=-1*g.wheelDeltaX),"axis"in g&&g.axis===g.HORIZONTAL_AXIS&&(l=-1*m,m=0),j=0===m?l:m,"deltaY"in g&&(m=-1*g.deltaY,j=m),"deltaX"in g&&(l=g.deltaX,0===m&&(j=-1*l)),0!==m||0!==l){if(1===g.deltaMode){var q=a.data(this,"mousewheel-line-height");j*=q,m*=q,l*=q}else if(2===g.deltaMode){var r=a.data(this,"mousewheel-page-height");j*=r,m*=r,l*=r}if(n=Math.max(Math.abs(m),Math.abs(l)),(!f||f>n)&&(f=n,d(g,n)&&(f/=40)),d(g,n)&&(j/=40,l/=40,m/=40),j=Math[j>=1?"floor":"ceil"](j/f),l=Math[l>=1?"floor":"ceil"](l/f),m=Math[m>=1?"floor":"ceil"](m/f),k.settings.normalizeOffset&&this.getBoundingClientRect){var s=this.getBoundingClientRect();o=b.clientX-s.left,p=b.clientY-s.top}return b.deltaX=l,b.deltaY=m,b.deltaFactor=f,b.offsetX=o,b.offsetY=p,b.deltaMode=0,h.unshift(b,j,l,m),e&&clearTimeout(e),e=setTimeout(c,200),(a.event.dispatch||a.event.handle).apply(this,h)}}function c(){f=null}function d(a,b){return k.settings.adjustOldDeltas&&"mousewheel"===a.type&&b%120===0}var e,f,g=["wheel","mousewheel","DOMMouseScroll","MozMousePixelScroll"],h="onwheel"in document||document.documentMode>=9?["wheel"]:["mousewheel","DomMouseScroll","MozMousePixelScroll"],i=Array.prototype.slice;if(a.event.fixHooks)for(var j=g.length;j;)a.event.fixHooks[g[--j]]=a.event.mouseHooks;var k=a.event.special.mousewheel={version:"3.1.12",setup:function(){if(this.addEventListener)for(var c=h.length;c;)this.addEventListener(h[--c],b,!1);else this.onmousewheel=b;a.data(this,"mousewheel-line-height",k.getLineHeight(this)),a.data(this,"mousewheel-page-height",k.getPageHeight(this))},teardown:function(){if(this.removeEventListener)for(var c=h.length;c;)this.removeEventListener(h[--c],b,!1);else this.onmousewheel=null;a.removeData(this,"mousewheel-line-height"),a.removeData(this,"mousewheel-page-height")},getLineHeight:function(b){var c=a(b),d=c["offsetParent"in a.fn?"offsetParent":"parent"]();return d.length||(d=a("body")),parseInt(d.css("fontSize"),10)||parseInt(c.css("fontSize"),10)||16},getPageHeight:function(b){return a(b).height()},settings:{adjustOldDeltas:!0,normalizeOffset:!0}};a.fn.extend({mousewheel:function(a){return a?this.bind("mousewheel",a):this.trigger("mousewheel")},unmousewheel:function(a){return this.unbind("mousewheel",a)}})});
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
/*! lazysizes - v1.1.3 -  Licensed MIT */
!function(a,b){var c=b(a,a.document);a.lazySizes=c,"object"==typeof module&&module.exports?module.exports=c:"function"==typeof define&&define.amd&&define(c)}(window,function(a,b){"use strict";if(b.getElementsByClassName){var c,d=b.documentElement,e=a.addEventListener,f=a.setTimeout,g=a.requestAnimationFrame||f,h=/^picture$/i,i=["load","error","lazyincluded","_lazyloaded"],j=function(a,b){var c=new RegExp("(\\s|^)"+b+"(\\s|$)");return a.className.match(c)&&c},k=function(a,b){j(a,b)||(a.className+=" "+b)},l=function(a,b){var c;(c=j(a,b))&&(a.className=a.className.replace(c," "))},m=function(a,b,c){var d=c?"addEventListener":"removeEventListener";c&&m(a,b),i.forEach(function(c){a[d](c,b)})},n=function(a,c,d,e,f){var g=b.createEvent("CustomEvent");return g.initCustomEvent(c,!e,!f,d||{}),g.details=g.detail,a.dispatchEvent(g),g},o=function(b,d){var e;a.HTMLPictureElement||((e=a.picturefill||a.respimage||c.pf)?e({reevaluate:!0,elements:[b]}):d&&d.src&&(b.src=d.src))},p=function(a,b){return getComputedStyle(a,null)[b]},q=function(a,b,d){for(d=d||a.offsetWidth;d<c.minSize&&b&&!a._lazysizesWidth;)d=b.offsetWidth,b=b.parentNode;return d},r=function(b){var d,e=0,h=a.Date,i=function(){d=!1,e=h.now(),b()},j=function(){f(i)},k=function(){g(j)};return function(){if(!d){var a=c.throttle-(h.now()-e);d=!0,9>a&&(a=9),f(k,a)}}},s=function(){var i,q,s,u,v,w,x,y,z,A,B,C,D,E=/^img$/i,F=/^iframe$/i,G="onscroll"in a&&!/glebot/.test(navigator.userAgent),H=0,I=0,J=0,K=1,L=function(a){J--,a&&a.target&&m(a.target,L),(!a||0>J||!a.target)&&(J=0)},M=function(a,b){var c,d=a,e="hidden"!=p(a,"visibility");for(y-=b,B+=b,z-=b,A+=b;e&&(d=d.offsetParent);)e=(p(d,"opacity")||1)>0,e&&"visible"!=p(d,"overflow")&&(c=d.getBoundingClientRect(),e=A>c.left&&z<c.right&&B>c.top-1&&y<c.bottom+1);return e},N=function(){var a,b,d,e,f,g,h,j,k;if((v=c.loadMode)&&8>J&&(a=i.length)){for(b=0,K++,D>I&&1>J&&K>3&&v>2?(I=D,K=0):I=I!=C&&v>1&&K>2&&6>J?C:H;a>b;b++)i[b]&&!i[b]._lazyRace&&(G?((j=i[b].getAttribute("data-expand"))&&(g=1*j)||(g=I),k!==g&&(w=innerWidth+g,x=innerHeight+g,h=-1*g,k=g),d=i[b].getBoundingClientRect(),(B=d.bottom)>=h&&(y=d.top)<=x&&(A=d.right)>=h&&(z=d.left)<=w&&(B||A||z||y)&&(s&&3>J&&!j&&(3>v||4>K)||M(i[b],g))?(S(i[b],d.width),f=!0):!f&&s&&!e&&3>J&&4>K&&v>2&&(q[0]||c.preloadAfterLoad)&&(q[0]||!j&&(B||A||z||y||"auto"!=i[b].getAttribute(c.sizesAttr)))&&(e=q[0]||i[b])):S(i[b]));e&&!f&&S(e)}},O=r(N),P=function(a){k(a.target,c.loadedClass),l(a.target,c.loadingClass),m(a.target,P)},Q=function(a,b){try{a.contentWindow.location.replace(b)}catch(c){a.setAttribute("src",b)}},R=function(){var a,b=[],c=function(){for(;b.length;)b.shift()();a=!1};return function(d){b.push(d),a||(a=!0,g(c))}}(),S=function(a,b){var d,e,g,i,p,q,r,v,w,x,y,z=E.test(a.nodeName),A=a.getAttribute(c.sizesAttr)||a.getAttribute("sizes"),B="auto"==A;(!B&&s||!z||!a.src&&!a.srcset||a.complete||j(a,c.errorClass))&&(a._lazyRace=!0,J++,R(function(){if(a._lazyRace&&delete a._lazyRace,l(a,c.lazyClass),!(w=n(a,"lazybeforeunveil")).defaultPrevented){if(A&&(B?(t.updateElem(a,!0,b),k(a,c.autosizesClass)):a.setAttribute("sizes",A)),q=a.getAttribute(c.srcsetAttr),p=a.getAttribute(c.srcAttr),z&&(r=a.parentNode,v=r&&h.test(r.nodeName||"")),x=w.detail.firesLoad||"src"in a&&(q||p||v),w={target:a},x&&(m(a,L,!0),clearTimeout(u),u=f(L,2500),k(a,c.loadingClass),m(a,P,!0)),v)for(d=r.getElementsByTagName("source"),e=0,g=d.length;g>e;e++)(y=c.customMedia[d[e].getAttribute("data-media")||d[e].getAttribute("media")])&&d[e].setAttribute("media",y),i=d[e].getAttribute(c.srcsetAttr),i&&d[e].setAttribute("srcset",i);q?a.setAttribute("srcset",q):p&&(F.test(a.nodeName)?Q(a,p):a.setAttribute("src",p)),(q||v)&&o(a,{src:p})}(!x||a.complete)&&(x?L(w):J--,P(w))}))},T=function(){var a,b=function(){c.loadMode=3,O()};s=!0,K+=8,c.loadMode=3,e("scroll",function(){3==c.loadMode&&(c.loadMode=2),clearTimeout(a),a=f(b,99)},!0)};return{_:function(){i=b.getElementsByClassName(c.lazyClass),q=b.getElementsByClassName(c.lazyClass+" "+c.preloadClass),C=c.expand,D=Math.round(C*c.expFactor),e("scroll",O,!0),e("resize",O,!0),a.MutationObserver?new MutationObserver(O).observe(d,{childList:!0,subtree:!0,attributes:!0}):(d.addEventListener("DOMNodeInserted",O,!0),d.addEventListener("DOMAttrModified",O,!0),setInterval(O,999)),e("hashchange",O,!0),["focus","mouseover","click","load","transitionend","animationend","webkitAnimationEnd"].forEach(function(a){b.addEventListener(a,O,!0)}),(s=/d$|^c/.test(b.readyState))?T():(e("load",T),b.addEventListener("DOMContentLoaded",O)),O()},checkElems:O,unveil:S}}(),t=function(){var a,d=function(a,b,c){var d,e,f,g,i=a.parentNode;if(i&&(c=q(a,i,c),g=n(a,"lazybeforesizes",{width:c,dataAttr:!!b}),!g.defaultPrevented&&(c=g.detail.width,c&&c!==a._lazysizesWidth))){if(a._lazysizesWidth=c,c+="px",a.setAttribute("sizes",c),h.test(i.nodeName||""))for(d=i.getElementsByTagName("source"),e=0,f=d.length;f>e;e++)d[e].setAttribute("sizes",c);g.detail.dataAttr||o(a,g.detail)}},f=function(){var b,c=a.length;if(c)for(b=0;c>b;b++)d(a[b])},g=r(f);return{_:function(){a=b.getElementsByClassName(c.autosizesClass),e("resize",g)},checkElems:g,updateElem:d}}(),u=function(){u.i||(u.i=!0,t._(),s._())};return function(){var b,d={lazyClass:"lazyload",loadedClass:"lazyloaded",loadingClass:"lazyloading",preloadClass:"lazypreload",errorClass:"lazyerror",autosizesClass:"lazyautosizes",srcAttr:"data-src",srcsetAttr:"data-srcset",sizesAttr:"data-sizes",minSize:40,customMedia:{},init:!0,expFactor:2,expand:359,loadMode:2,throttle:125};c=a.lazySizesConfig||a.lazysizesConfig||{};for(b in d)b in c||(c[b]=d[b]);a.lazySizesConfig=c,f(function(){c.init&&u()})}(),{cfg:c,autoSizer:t,loader:s,init:u,uP:o,aC:k,rC:l,hC:j,fire:n,gW:q}}});
"use strict";

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var LoadingHooks = (function ($) {
	var LoadingHooks = (function () {
		function LoadingHooks() {
			_classCallCheck(this, LoadingHooks);

			this._hooksEachChain = $.Deferred().resolve();
			this._hooksAny = [];
		}

		_createClass(LoadingHooks, [{
			key: "makeHookEach",
			value: function makeHookEach() {
				var newHook = this._makeHook();
				this._hooksEachChain = this._hooksEachChain.then(function () {
					return newHook;
				});
				return newHook;
			}
		}, {
			key: "makeHookAny",
			value: function makeHookAny() {
				var newHook = this._makeHook();
				this._hooksAny.push(newHook);
				return newHook;
			}
		}, {
			key: "whenReady",
			value: function whenReady(doAfterReady) {
				var hooksAny = this._hooksAny,
						i = 0;

				$.when(this._hooksEachChain).then(doAfterReady);

				for (; i < hooksAny.length; i++) {
					$.when(hooksAny[i]).then(doAfterReady);
				}
			}
		}, {
			key: "_makeHook",
			value: function _makeHook() {
				var defer = $.Deferred();
				return defer;
			}
		}]);

		return LoadingHooks;
	})();

	return LoadingHooks;
})(jQuery);
/*!
 * Masonry PACKAGED v3.3.0
 * Cascading grid layout library
 * http://masonry.desandro.com
 * MIT License
 * by David DeSandro
 */

!function(a){function b(){}function c(a){function c(b){b.prototype.option||(b.prototype.option=function(b){a.isPlainObject(b)&&(this.options=a.extend(!0,this.options,b))})}function e(b,c){a.fn[b]=function(e){if("string"==typeof e){for(var g=d.call(arguments,1),h=0,i=this.length;i>h;h++){var j=this[h],k=a.data(j,b);if(k)if(a.isFunction(k[e])&&"_"!==e.charAt(0)){var l=k[e].apply(k,g);if(void 0!==l)return l}else f("no such method '"+e+"' for "+b+" instance");else f("cannot call methods on "+b+" prior to initialization; attempted to call '"+e+"'")}return this}return this.each(function(){var d=a.data(this,b);d?(d.option(e),d._init()):(d=new c(this,e),a.data(this,b,d))})}}if(a){var f="undefined"==typeof console?b:function(a){console.error(a)};return a.bridget=function(a,b){c(b),e(a,b)},a.bridget}}var d=Array.prototype.slice;"function"==typeof define&&define.amd?define("jquery-bridget/jquery.bridget",["jquery"],c):c("object"==typeof exports?require("jquery"):a.jQuery)}(window),function(a){function b(b){var c=a.event;return c.target=c.target||c.srcElement||b,c}var c=document.documentElement,d=function(){};c.addEventListener?d=function(a,b,c){a.addEventListener(b,c,!1)}:c.attachEvent&&(d=function(a,c,d){a[c+d]=d.handleEvent?function(){var c=b(a);d.handleEvent.call(d,c)}:function(){var c=b(a);d.call(a,c)},a.attachEvent("on"+c,a[c+d])});var e=function(){};c.removeEventListener?e=function(a,b,c){a.removeEventListener(b,c,!1)}:c.detachEvent&&(e=function(a,b,c){a.detachEvent("on"+b,a[b+c]);try{delete a[b+c]}catch(d){a[b+c]=void 0}});var f={bind:d,unbind:e};"function"==typeof define&&define.amd?define("eventie/eventie",f):"object"==typeof exports?module.exports=f:a.eventie=f}(window),function(){function a(){}function b(a,b){for(var c=a.length;c--;)if(a[c].listener===b)return c;return-1}function c(a){return function(){return this[a].apply(this,arguments)}}var d=a.prototype,e=this,f=e.EventEmitter;d.getListeners=function(a){var b,c,d=this._getEvents();if(a instanceof RegExp){b={};for(c in d)d.hasOwnProperty(c)&&a.test(c)&&(b[c]=d[c])}else b=d[a]||(d[a]=[]);return b},d.flattenListeners=function(a){var b,c=[];for(b=0;b<a.length;b+=1)c.push(a[b].listener);return c},d.getListenersAsObject=function(a){var b,c=this.getListeners(a);return c instanceof Array&&(b={},b[a]=c),b||c},d.addListener=function(a,c){var d,e=this.getListenersAsObject(a),f="object"==typeof c;for(d in e)e.hasOwnProperty(d)&&-1===b(e[d],c)&&e[d].push(f?c:{listener:c,once:!1});return this},d.on=c("addListener"),d.addOnceListener=function(a,b){return this.addListener(a,{listener:b,once:!0})},d.once=c("addOnceListener"),d.defineEvent=function(a){return this.getListeners(a),this},d.defineEvents=function(a){for(var b=0;b<a.length;b+=1)this.defineEvent(a[b]);return this},d.removeListener=function(a,c){var d,e,f=this.getListenersAsObject(a);for(e in f)f.hasOwnProperty(e)&&(d=b(f[e],c),-1!==d&&f[e].splice(d,1));return this},d.off=c("removeListener"),d.addListeners=function(a,b){return this.manipulateListeners(!1,a,b)},d.removeListeners=function(a,b){return this.manipulateListeners(!0,a,b)},d.manipulateListeners=function(a,b,c){var d,e,f=a?this.removeListener:this.addListener,g=a?this.removeListeners:this.addListeners;if("object"!=typeof b||b instanceof RegExp)for(d=c.length;d--;)f.call(this,b,c[d]);else for(d in b)b.hasOwnProperty(d)&&(e=b[d])&&("function"==typeof e?f.call(this,d,e):g.call(this,d,e));return this},d.removeEvent=function(a){var b,c=typeof a,d=this._getEvents();if("string"===c)delete d[a];else if(a instanceof RegExp)for(b in d)d.hasOwnProperty(b)&&a.test(b)&&delete d[b];else delete this._events;return this},d.removeAllListeners=c("removeEvent"),d.emitEvent=function(a,b){var c,d,e,f,g=this.getListenersAsObject(a);for(e in g)if(g.hasOwnProperty(e))for(d=g[e].length;d--;)c=g[e][d],c.once===!0&&this.removeListener(a,c.listener),f=c.listener.apply(this,b||[]),f===this._getOnceReturnValue()&&this.removeListener(a,c.listener);return this},d.trigger=c("emitEvent"),d.emit=function(a){var b=Array.prototype.slice.call(arguments,1);return this.emitEvent(a,b)},d.setOnceReturnValue=function(a){return this._onceReturnValue=a,this},d._getOnceReturnValue=function(){return this.hasOwnProperty("_onceReturnValue")?this._onceReturnValue:!0},d._getEvents=function(){return this._events||(this._events={})},a.noConflict=function(){return e.EventEmitter=f,a},"function"==typeof define&&define.amd?define("eventEmitter/EventEmitter",[],function(){return a}):"object"==typeof module&&module.exports?module.exports=a:e.EventEmitter=a}.call(this),function(a){function b(a){if(a){if("string"==typeof d[a])return a;a=a.charAt(0).toUpperCase()+a.slice(1);for(var b,e=0,f=c.length;f>e;e++)if(b=c[e]+a,"string"==typeof d[b])return b}}var c="Webkit Moz ms Ms O".split(" "),d=document.documentElement.style;"function"==typeof define&&define.amd?define("get-style-property/get-style-property",[],function(){return b}):"object"==typeof exports?module.exports=b:a.getStyleProperty=b}(window),function(a){function b(a){var b=parseFloat(a),c=-1===a.indexOf("%")&&!isNaN(b);return c&&b}function c(){}function d(){for(var a={width:0,height:0,innerWidth:0,innerHeight:0,outerWidth:0,outerHeight:0},b=0,c=g.length;c>b;b++){var d=g[b];a[d]=0}return a}function e(c){function e(){if(!m){m=!0;var d=a.getComputedStyle;if(j=function(){var a=d?function(a){return d(a,null)}:function(a){return a.currentStyle};return function(b){var c=a(b);return c||f("Style returned "+c+". Are you running this code in a hidden iframe on Firefox? See http://bit.ly/getsizebug1"),c}}(),k=c("boxSizing")){var e=document.createElement("div");e.style.width="200px",e.style.padding="1px 2px 3px 4px",e.style.borderStyle="solid",e.style.borderWidth="1px 2px 3px 4px",e.style[k]="border-box";var g=document.body||document.documentElement;g.appendChild(e);var h=j(e);l=200===b(h.width),g.removeChild(e)}}}function h(a){if(e(),"string"==typeof a&&(a=document.querySelector(a)),a&&"object"==typeof a&&a.nodeType){var c=j(a);if("none"===c.display)return d();var f={};f.width=a.offsetWidth,f.height=a.offsetHeight;for(var h=f.isBorderBox=!(!k||!c[k]||"border-box"!==c[k]),m=0,n=g.length;n>m;m++){var o=g[m],p=c[o];p=i(a,p);var q=parseFloat(p);f[o]=isNaN(q)?0:q}var r=f.paddingLeft+f.paddingRight,s=f.paddingTop+f.paddingBottom,t=f.marginLeft+f.marginRight,u=f.marginTop+f.marginBottom,v=f.borderLeftWidth+f.borderRightWidth,w=f.borderTopWidth+f.borderBottomWidth,x=h&&l,y=b(c.width);y!==!1&&(f.width=y+(x?0:r+v));var z=b(c.height);return z!==!1&&(f.height=z+(x?0:s+w)),f.innerWidth=f.width-(r+v),f.innerHeight=f.height-(s+w),f.outerWidth=f.width+t,f.outerHeight=f.height+u,f}}function i(b,c){if(a.getComputedStyle||-1===c.indexOf("%"))return c;var d=b.style,e=d.left,f=b.runtimeStyle,g=f&&f.left;return g&&(f.left=b.currentStyle.left),d.left=c,c=d.pixelLeft,d.left=e,g&&(f.left=g),c}var j,k,l,m=!1;return h}var f="undefined"==typeof console?c:function(a){console.error(a)},g=["paddingLeft","paddingRight","paddingTop","paddingBottom","marginLeft","marginRight","marginTop","marginBottom","borderLeftWidth","borderRightWidth","borderTopWidth","borderBottomWidth"];"function"==typeof define&&define.amd?define("get-size/get-size",["get-style-property/get-style-property"],e):"object"==typeof exports?module.exports=e(require("desandro-get-style-property")):a.getSize=e(a.getStyleProperty)}(window),function(a){function b(a){"function"==typeof a&&(b.isReady?a():g.push(a))}function c(a){var c="readystatechange"===a.type&&"complete"!==f.readyState;b.isReady||c||d()}function d(){b.isReady=!0;for(var a=0,c=g.length;c>a;a++){var d=g[a];d()}}function e(e){return"complete"===f.readyState?d():(e.bind(f,"DOMContentLoaded",c),e.bind(f,"readystatechange",c),e.bind(a,"load",c)),b}var f=a.document,g=[];b.isReady=!1,"function"==typeof define&&define.amd?define("doc-ready/doc-ready",["eventie/eventie"],e):"object"==typeof exports?module.exports=e(require("eventie")):a.docReady=e(a.eventie)}(window),function(a){function b(a,b){return a[g](b)}function c(a){if(!a.parentNode){var b=document.createDocumentFragment();b.appendChild(a)}}function d(a,b){c(a);for(var d=a.parentNode.querySelectorAll(b),e=0,f=d.length;f>e;e++)if(d[e]===a)return!0;return!1}function e(a,d){return c(a),b(a,d)}var f,g=function(){if(a.matches)return"matches";if(a.matchesSelector)return"matchesSelector";for(var b=["webkit","moz","ms","o"],c=0,d=b.length;d>c;c++){var e=b[c],f=e+"MatchesSelector";if(a[f])return f}}();if(g){var h=document.createElement("div"),i=b(h,"div");f=i?b:e}else f=d;"function"==typeof define&&define.amd?define("matches-selector/matches-selector",[],function(){return f}):"object"==typeof exports?module.exports=f:window.matchesSelector=f}(Element.prototype),function(a,b){"function"==typeof define&&define.amd?define("fizzy-ui-utils/utils",["doc-ready/doc-ready","matches-selector/matches-selector"],function(c,d){return b(a,c,d)}):"object"==typeof exports?module.exports=b(a,require("doc-ready"),require("desandro-matches-selector")):a.fizzyUIUtils=b(a,a.docReady,a.matchesSelector)}(window,function(a,b,c){var d={};d.extend=function(a,b){for(var c in b)a[c]=b[c];return a},d.modulo=function(a,b){return(a%b+b)%b};var e=Object.prototype.toString;d.isArray=function(a){return"[object Array]"==e.call(a)},d.makeArray=function(a){var b=[];if(d.isArray(a))b=a;else if(a&&"number"==typeof a.length)for(var c=0,e=a.length;e>c;c++)b.push(a[c]);else b.push(a);return b},d.indexOf=Array.prototype.indexOf?function(a,b){return a.indexOf(b)}:function(a,b){for(var c=0,d=a.length;d>c;c++)if(a[c]===b)return c;return-1},d.removeFrom=function(a,b){var c=d.indexOf(a,b);-1!=c&&a.splice(c,1)},d.isElement="function"==typeof HTMLElement||"object"==typeof HTMLElement?function(a){return a instanceof HTMLElement}:function(a){return a&&"object"==typeof a&&1==a.nodeType&&"string"==typeof a.nodeName},d.setText=function(){function a(a,c){b=b||(void 0!==document.documentElement.textContent?"textContent":"innerText"),a[b]=c}var b;return a}(),d.getParent=function(a,b){for(;a!=document.body;)if(a=a.parentNode,c(a,b))return a},d.getQueryElement=function(a){return"string"==typeof a?document.querySelector(a):a},d.handleEvent=function(a){var b="on"+a.type;this[b]&&this[b](a)},d.filterFindElements=function(a,b){a=d.makeArray(a);for(var e=[],f=0,g=a.length;g>f;f++){var h=a[f];if(d.isElement(h))if(b){c(h,b)&&e.push(h);for(var i=h.querySelectorAll(b),j=0,k=i.length;k>j;j++)e.push(i[j])}else e.push(h)}return e},d.debounceMethod=function(a,b,c){var d=a.prototype[b],e=b+"Timeout";a.prototype[b]=function(){var a=this[e];a&&clearTimeout(a);var b=arguments,f=this;this[e]=setTimeout(function(){d.apply(f,b),delete f[e]},c||100)}},d.toDashed=function(a){return a.replace(/(.)([A-Z])/g,function(a,b,c){return b+"-"+c}).toLowerCase()};var f=a.console;return d.htmlInit=function(c,e){b(function(){for(var b=d.toDashed(e),g=document.querySelectorAll(".js-"+b),h="data-"+b+"-options",i=0,j=g.length;j>i;i++){var k,l=g[i],m=l.getAttribute(h);try{k=m&&JSON.parse(m)}catch(n){f&&f.error("Error parsing "+h+" on "+l.nodeName.toLowerCase()+(l.id?"#"+l.id:"")+": "+n);continue}var o=new c(l,k),p=a.jQuery;p&&p.data(l,e,o)}})},d}),function(a,b){"function"==typeof define&&define.amd?define("outlayer/item",["eventEmitter/EventEmitter","get-size/get-size","get-style-property/get-style-property","fizzy-ui-utils/utils"],function(c,d,e,f){return b(a,c,d,e,f)}):"object"==typeof exports?module.exports=b(a,require("wolfy87-eventemitter"),require("get-size"),require("desandro-get-style-property"),require("fizzy-ui-utils")):(a.Outlayer={},a.Outlayer.Item=b(a,a.EventEmitter,a.getSize,a.getStyleProperty,a.fizzyUIUtils))}(window,function(a,b,c,d,e){function f(a){for(var b in a)return!1;return b=null,!0}function g(a,b){a&&(this.element=a,this.layout=b,this.position={x:0,y:0},this._create())}var h=a.getComputedStyle,i=h?function(a){return h(a,null)}:function(a){return a.currentStyle},j=d("transition"),k=d("transform"),l=j&&k,m=!!d("perspective"),n={WebkitTransition:"webkitTransitionEnd",MozTransition:"transitionend",OTransition:"otransitionend",transition:"transitionend"}[j],o=["transform","transition","transitionDuration","transitionProperty"],p=function(){for(var a={},b=0,c=o.length;c>b;b++){var e=o[b],f=d(e);f&&f!==e&&(a[e]=f)}return a}();e.extend(g.prototype,b.prototype),g.prototype._create=function(){this._transn={ingProperties:{},clean:{},onEnd:{}},this.css({position:"absolute"})},g.prototype.handleEvent=function(a){var b="on"+a.type;this[b]&&this[b](a)},g.prototype.getSize=function(){this.size=c(this.element)},g.prototype.css=function(a){var b=this.element.style;for(var c in a){var d=p[c]||c;b[d]=a[c]}},g.prototype.getPosition=function(){var a=i(this.element),b=this.layout.options,c=b.isOriginLeft,d=b.isOriginTop,e=parseInt(a[c?"left":"right"],10),f=parseInt(a[d?"top":"bottom"],10);e=isNaN(e)?0:e,f=isNaN(f)?0:f;var g=this.layout.size;e-=c?g.paddingLeft:g.paddingRight,f-=d?g.paddingTop:g.paddingBottom,this.position.x=e,this.position.y=f},g.prototype.layoutPosition=function(){var a=this.layout.size,b=this.layout.options,c={},d=b.isOriginLeft?"paddingLeft":"paddingRight",e=b.isOriginLeft?"left":"right",f=b.isOriginLeft?"right":"left",g=this.position.x+a[d];g=b.percentPosition&&!b.isHorizontal?g/a.width*100+"%":g+"px",c[e]=g,c[f]="";var h=b.isOriginTop?"paddingTop":"paddingBottom",i=b.isOriginTop?"top":"bottom",j=b.isOriginTop?"bottom":"top",k=this.position.y+a[h];k=b.percentPosition&&b.isHorizontal?k/a.height*100+"%":k+"px",c[i]=k,c[j]="",this.css(c),this.emitEvent("layout",[this])};var q=m?function(a,b){return"translate3d("+a+"px, "+b+"px, 0)"}:function(a,b){return"translate("+a+"px, "+b+"px)"};g.prototype._transitionTo=function(a,b){this.getPosition();var c=this.position.x,d=this.position.y,e=parseInt(a,10),f=parseInt(b,10),g=e===this.position.x&&f===this.position.y;if(this.setPosition(a,b),g&&!this.isTransitioning)return void this.layoutPosition();var h=a-c,i=b-d,j={},k=this.layout.options;h=k.isOriginLeft?h:-h,i=k.isOriginTop?i:-i,j.transform=q(h,i),this.transition({to:j,onTransitionEnd:{transform:this.layoutPosition},isCleaning:!0})},g.prototype.goTo=function(a,b){this.setPosition(a,b),this.layoutPosition()},g.prototype.moveTo=l?g.prototype._transitionTo:g.prototype.goTo,g.prototype.setPosition=function(a,b){this.position.x=parseInt(a,10),this.position.y=parseInt(b,10)},g.prototype._nonTransition=function(a){this.css(a.to),a.isCleaning&&this._removeStyles(a.to);for(var b in a.onTransitionEnd)a.onTransitionEnd[b].call(this)},g.prototype._transition=function(a){if(!parseFloat(this.layout.options.transitionDuration))return void this._nonTransition(a);var b=this._transn;for(var c in a.onTransitionEnd)b.onEnd[c]=a.onTransitionEnd[c];for(c in a.to)b.ingProperties[c]=!0,a.isCleaning&&(b.clean[c]=!0);if(a.from){this.css(a.from);var d=this.element.offsetHeight;d=null}this.enableTransition(a.to),this.css(a.to),this.isTransitioning=!0};var r=k&&e.toDashed(k)+",opacity";g.prototype.enableTransition=function(){this.isTransitioning||(this.css({transitionProperty:r,transitionDuration:this.layout.options.transitionDuration}),this.element.addEventListener(n,this,!1))},g.prototype.transition=g.prototype[j?"_transition":"_nonTransition"],g.prototype.onwebkitTransitionEnd=function(a){this.ontransitionend(a)},g.prototype.onotransitionend=function(a){this.ontransitionend(a)};var s={"-webkit-transform":"transform","-moz-transform":"transform","-o-transform":"transform"};g.prototype.ontransitionend=function(a){if(a.target===this.element){var b=this._transn,c=s[a.propertyName]||a.propertyName;if(delete b.ingProperties[c],f(b.ingProperties)&&this.disableTransition(),c in b.clean&&(this.element.style[a.propertyName]="",delete b.clean[c]),c in b.onEnd){var d=b.onEnd[c];d.call(this),delete b.onEnd[c]}this.emitEvent("transitionEnd",[this])}},g.prototype.disableTransition=function(){this.removeTransitionStyles(),this.element.removeEventListener(n,this,!1),this.isTransitioning=!1},g.prototype._removeStyles=function(a){var b={};for(var c in a)b[c]="";this.css(b)};var t={transitionProperty:"",transitionDuration:""};return g.prototype.removeTransitionStyles=function(){this.css(t)},g.prototype.removeElem=function(){this.element.parentNode.removeChild(this.element),this.css({display:""}),this.emitEvent("remove",[this])},g.prototype.remove=function(){if(!j||!parseFloat(this.layout.options.transitionDuration))return void this.removeElem();var a=this;this.once("transitionEnd",function(){a.removeElem()}),this.hide()},g.prototype.reveal=function(){delete this.isHidden,this.css({display:""});var a=this.layout.options,b={},c=this.getHideRevealTransitionEndProperty("visibleStyle");b[c]=this.onRevealTransitionEnd,this.transition({from:a.hiddenStyle,to:a.visibleStyle,isCleaning:!0,onTransitionEnd:b})},g.prototype.onRevealTransitionEnd=function(){this.isHidden||this.emitEvent("reveal")},g.prototype.getHideRevealTransitionEndProperty=function(a){var b=this.layout.options[a];if(b.opacity)return"opacity";for(var c in b)return c},g.prototype.hide=function(){this.isHidden=!0,this.css({display:""});var a=this.layout.options,b={},c=this.getHideRevealTransitionEndProperty("hiddenStyle");b[c]=this.onHideTransitionEnd,this.transition({from:a.visibleStyle,to:a.hiddenStyle,isCleaning:!0,onTransitionEnd:b})},g.prototype.onHideTransitionEnd=function(){this.isHidden&&(this.css({display:"none"}),this.emitEvent("hide"))},g.prototype.destroy=function(){this.css({position:"",left:"",right:"",top:"",bottom:"",transition:"",transform:""})},g}),function(a,b){"function"==typeof define&&define.amd?define("outlayer/outlayer",["eventie/eventie","eventEmitter/EventEmitter","get-size/get-size","fizzy-ui-utils/utils","./item"],function(c,d,e,f,g){return b(a,c,d,e,f,g)}):"object"==typeof exports?module.exports=b(a,require("eventie"),require("wolfy87-eventemitter"),require("get-size"),require("fizzy-ui-utils"),require("./item")):a.Outlayer=b(a,a.eventie,a.EventEmitter,a.getSize,a.fizzyUIUtils,a.Outlayer.Item)}(window,function(a,b,c,d,e,f){function g(a,b){var c=e.getQueryElement(a);if(!c)return void(h&&h.error("Bad element for "+this.constructor.namespace+": "+(c||a)));this.element=c,i&&(this.$element=i(this.element)),this.options=e.extend({},this.constructor.defaults),this.option(b);var d=++k;this.element.outlayerGUID=d,l[d]=this,this._create(),this.options.isInitLayout&&this.layout()}var h=a.console,i=a.jQuery,j=function(){},k=0,l={};return g.namespace="outlayer",g.Item=f,g.defaults={containerStyle:{position:"relative"},isInitLayout:!0,isOriginLeft:!0,isOriginTop:!0,isResizeBound:!0,isResizingContainer:!0,transitionDuration:"0.4s",hiddenStyle:{opacity:0,transform:"scale(0.001)"},visibleStyle:{opacity:1,transform:"scale(1)"}},e.extend(g.prototype,c.prototype),g.prototype.option=function(a){e.extend(this.options,a)},g.prototype._create=function(){this.reloadItems(),this.stamps=[],this.stamp(this.options.stamp),e.extend(this.element.style,this.options.containerStyle),this.options.isResizeBound&&this.bindResize()},g.prototype.reloadItems=function(){this.items=this._itemize(this.element.children)},g.prototype._itemize=function(a){for(var b=this._filterFindItemElements(a),c=this.constructor.Item,d=[],e=0,f=b.length;f>e;e++){var g=b[e],h=new c(g,this);d.push(h)}return d},g.prototype._filterFindItemElements=function(a){return e.filterFindElements(a,this.options.itemSelector)},g.prototype.getItemElements=function(){for(var a=[],b=0,c=this.items.length;c>b;b++)a.push(this.items[b].element);return a},g.prototype.layout=function(){this._resetLayout(),this._manageStamps();var a=void 0!==this.options.isLayoutInstant?this.options.isLayoutInstant:!this._isLayoutInited;this.layoutItems(this.items,a),this._isLayoutInited=!0},g.prototype._init=g.prototype.layout,g.prototype._resetLayout=function(){this.getSize()},g.prototype.getSize=function(){this.size=d(this.element)},g.prototype._getMeasurement=function(a,b){var c,f=this.options[a];f?("string"==typeof f?c=this.element.querySelector(f):e.isElement(f)&&(c=f),this[a]=c?d(c)[b]:f):this[a]=0},g.prototype.layoutItems=function(a,b){a=this._getItemsForLayout(a),this._layoutItems(a,b),this._postLayout()},g.prototype._getItemsForLayout=function(a){for(var b=[],c=0,d=a.length;d>c;c++){var e=a[c];e.isIgnored||b.push(e)}return b},g.prototype._layoutItems=function(a,b){if(this._emitCompleteOnItems("layout",a),a&&a.length){for(var c=[],d=0,e=a.length;e>d;d++){var f=a[d],g=this._getItemLayoutPosition(f);g.item=f,g.isInstant=b||f.isLayoutInstant,c.push(g)}this._processLayoutQueue(c)}},g.prototype._getItemLayoutPosition=function(){return{x:0,y:0}},g.prototype._processLayoutQueue=function(a){for(var b=0,c=a.length;c>b;b++){var d=a[b];this._positionItem(d.item,d.x,d.y,d.isInstant)}},g.prototype._positionItem=function(a,b,c,d){d?a.goTo(b,c):a.moveTo(b,c)},g.prototype._postLayout=function(){this.resizeContainer()},g.prototype.resizeContainer=function(){if(this.options.isResizingContainer){var a=this._getContainerSize();a&&(this._setContainerMeasure(a.width,!0),this._setContainerMeasure(a.height,!1))}},g.prototype._getContainerSize=j,g.prototype._setContainerMeasure=function(a,b){if(void 0!==a){var c=this.size;c.isBorderBox&&(a+=b?c.paddingLeft+c.paddingRight+c.borderLeftWidth+c.borderRightWidth:c.paddingBottom+c.paddingTop+c.borderTopWidth+c.borderBottomWidth),a=Math.max(a,0),this.element.style[b?"width":"height"]=a+"px"}},g.prototype._emitCompleteOnItems=function(a,b){function c(){e.emitEvent(a+"Complete",[b])}function d(){g++,g===f&&c()}var e=this,f=b.length;if(!b||!f)return void c();for(var g=0,h=0,i=b.length;i>h;h++){var j=b[h];j.once(a,d)}},g.prototype.ignore=function(a){var b=this.getItem(a);b&&(b.isIgnored=!0)},g.prototype.unignore=function(a){var b=this.getItem(a);b&&delete b.isIgnored},g.prototype.stamp=function(a){if(a=this._find(a)){this.stamps=this.stamps.concat(a);for(var b=0,c=a.length;c>b;b++){var d=a[b];this.ignore(d)}}},g.prototype.unstamp=function(a){if(a=this._find(a))for(var b=0,c=a.length;c>b;b++){var d=a[b];e.removeFrom(this.stamps,d),this.unignore(d)}},g.prototype._find=function(a){return a?("string"==typeof a&&(a=this.element.querySelectorAll(a)),a=e.makeArray(a)):void 0},g.prototype._manageStamps=function(){if(this.stamps&&this.stamps.length){this._getBoundingRect();for(var a=0,b=this.stamps.length;b>a;a++){var c=this.stamps[a];this._manageStamp(c)}}},g.prototype._getBoundingRect=function(){var a=this.element.getBoundingClientRect(),b=this.size;this._boundingRect={left:a.left+b.paddingLeft+b.borderLeftWidth,top:a.top+b.paddingTop+b.borderTopWidth,right:a.right-(b.paddingRight+b.borderRightWidth),bottom:a.bottom-(b.paddingBottom+b.borderBottomWidth)}},g.prototype._manageStamp=j,g.prototype._getElementOffset=function(a){var b=a.getBoundingClientRect(),c=this._boundingRect,e=d(a),f={left:b.left-c.left-e.marginLeft,top:b.top-c.top-e.marginTop,right:c.right-b.right-e.marginRight,bottom:c.bottom-b.bottom-e.marginBottom};return f},g.prototype.handleEvent=function(a){var b="on"+a.type;this[b]&&this[b](a)},g.prototype.bindResize=function(){this.isResizeBound||(b.bind(a,"resize",this),this.isResizeBound=!0)},g.prototype.unbindResize=function(){this.isResizeBound&&b.unbind(a,"resize",this),this.isResizeBound=!1},g.prototype.onresize=function(){function a(){b.resize(),delete b.resizeTimeout}this.resizeTimeout&&clearTimeout(this.resizeTimeout);var b=this;this.resizeTimeout=setTimeout(a,100)},g.prototype.resize=function(){this.isResizeBound&&this.needsResizeLayout()&&this.layout()},g.prototype.needsResizeLayout=function(){var a=d(this.element),b=this.size&&a;return b&&a.innerWidth!==this.size.innerWidth},g.prototype.addItems=function(a){var b=this._itemize(a);return b.length&&(this.items=this.items.concat(b)),b},g.prototype.appended=function(a){var b=this.addItems(a);b.length&&(this.layoutItems(b,!0),this.reveal(b))},g.prototype.prepended=function(a){var b=this._itemize(a);if(b.length){var c=this.items.slice(0);this.items=b.concat(c),this._resetLayout(),this._manageStamps(),this.layoutItems(b,!0),this.reveal(b),this.layoutItems(c)}},g.prototype.reveal=function(a){this._emitCompleteOnItems("reveal",a);for(var b=a&&a.length,c=0;b&&b>c;c++){var d=a[c];d.reveal()}},g.prototype.hide=function(a){this._emitCompleteOnItems("hide",a);for(var b=a&&a.length,c=0;b&&b>c;c++){var d=a[c];d.hide()}},g.prototype.revealItemElements=function(a){var b=this.getItems(a);this.reveal(b)},g.prototype.hideItemElements=function(a){var b=this.getItems(a);this.hide(b)},g.prototype.getItem=function(a){for(var b=0,c=this.items.length;c>b;b++){var d=this.items[b];if(d.element===a)return d}},g.prototype.getItems=function(a){a=e.makeArray(a);for(var b=[],c=0,d=a.length;d>c;c++){var f=a[c],g=this.getItem(f);g&&b.push(g)}return b},g.prototype.remove=function(a){var b=this.getItems(a);if(this._emitCompleteOnItems("remove",b),b&&b.length)for(var c=0,d=b.length;d>c;c++){var f=b[c];f.remove(),e.removeFrom(this.items,f)}},g.prototype.destroy=function(){var a=this.element.style;a.height="",a.position="",a.width="";for(var b=0,c=this.items.length;c>b;b++){var d=this.items[b];d.destroy()}this.unbindResize();var e=this.element.outlayerGUID;delete l[e],delete this.element.outlayerGUID,i&&i.removeData(this.element,this.constructor.namespace)},g.data=function(a){a=e.getQueryElement(a);var b=a&&a.outlayerGUID;return b&&l[b]},g.create=function(a,b){function c(){g.apply(this,arguments)}return Object.create?c.prototype=Object.create(g.prototype):e.extend(c.prototype,g.prototype),c.prototype.constructor=c,c.defaults=e.extend({},g.defaults),e.extend(c.defaults,b),c.prototype.settings={},c.namespace=a,c.data=g.data,c.Item=function(){f.apply(this,arguments)},c.Item.prototype=new f,e.htmlInit(c,a),i&&i.bridget&&i.bridget(a,c),c},g.Item=f,g}),function(a,b){"function"==typeof define&&define.amd?define(["outlayer/outlayer","get-size/get-size","fizzy-ui-utils/utils"],b):"object"==typeof exports?module.exports=b(require("outlayer"),require("get-size"),require("fizzy-ui-utils")):a.Masonry=b(a.Outlayer,a.getSize,a.fizzyUIUtils)}(window,function(a,b,c){var d=a.create("masonry");return d.prototype._resetLayout=function(){this.getSize(),this._getMeasurement("columnWidth","outerWidth"),this._getMeasurement("gutter","outerWidth"),this.measureColumns();var a=this.cols;for(this.colYs=[];a--;)this.colYs.push(0);this.maxY=0},d.prototype.measureColumns=function(){if(this.getContainerWidth(),!this.columnWidth){var a=this.items[0],c=a&&a.element;this.columnWidth=c&&b(c).outerWidth||this.containerWidth}var d=this.columnWidth+=this.gutter,e=this.containerWidth+this.gutter,f=e/d,g=d-e%d,h=g&&1>g?"round":"floor";f=Math[h](f),this.cols=Math.max(f,1)},d.prototype.getContainerWidth=function(){var a=this.options.isFitWidth?this.element.parentNode:this.element,c=b(a);this.containerWidth=c&&c.innerWidth},d.prototype._getItemLayoutPosition=function(a){a.getSize();var b=a.size.outerWidth%this.columnWidth,d=b&&1>b?"round":"ceil",e=Math[d](a.size.outerWidth/this.columnWidth);e=Math.min(e,this.cols);for(var f=this._getColGroup(e),g=Math.min.apply(Math,f),h=c.indexOf(f,g),i={x:this.columnWidth*h,y:g},j=g+a.size.outerHeight,k=this.cols+1-f.length,l=0;k>l;l++)this.colYs[h+l]=j;return i},d.prototype._getColGroup=function(a){if(2>a)return this.colYs;for(var b=[],c=this.cols+1-a,d=0;c>d;d++){var e=this.colYs.slice(d,d+a);b[d]=Math.max.apply(Math,e)}return b},d.prototype._manageStamp=function(a){var c=b(a),d=this._getElementOffset(a),e=this.options.isOriginLeft?d.left:d.right,f=e+c.outerWidth,g=Math.floor(e/this.columnWidth);g=Math.max(0,g);var h=Math.floor(f/this.columnWidth);h-=f%this.columnWidth?0:1,h=Math.min(this.cols-1,h);for(var i=(this.options.isOriginTop?d.top:d.bottom)+c.outerHeight,j=g;h>=j;j++)this.colYs[j]=Math.max(i,this.colYs[j])},d.prototype._getContainerSize=function(){this.maxY=Math.max.apply(Math,this.colYs);var a={height:this.maxY};return this.options.isFitWidth&&(a.width=this._getContainerFitWidth()),a},d.prototype._getContainerFitWidth=function(){for(var a=0,b=this.cols;--b&&0===this.colYs[b];)a++;return(this.cols-a)*this.columnWidth-this.gutter},d.prototype.needsResizeLayout=function(){var a=this.containerWidth;return this.getContainerWidth(),a!==this.containerWidth},d});
eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('7(A 3c.3q!=="9"){3c.3q=9(e){9 t(){}t.5S=e;p 5R t}}(9(e,t,n){h r={1N:9(t,n){h r=c;r.$k=e(n);r.6=e.4M({},e.37.2B.6,r.$k.v(),t);r.2A=t;r.4L()},4L:9(){9 r(e){h n,r="";7(A t.6.33==="9"){t.6.33.R(c,[e])}l{1A(n 38 e.d){7(e.d.5M(n)){r+=e.d[n].1K}}t.$k.2y(r)}t.3t()}h t=c,n;7(A t.6.2H==="9"){t.6.2H.R(c,[t.$k])}7(A t.6.2O==="2Y"){n=t.6.2O;e.5K(n,r)}l{t.3t()}},3t:9(){h e=c;e.$k.v("d-4I",e.$k.2x("2w")).v("d-4F",e.$k.2x("H"));e.$k.z({2u:0});e.2t=e.6.q;e.4E();e.5v=0;e.1X=14;e.23()},23:9(){h e=c;7(e.$k.25().N===0){p b}e.1M();e.4C();e.$S=e.$k.25();e.E=e.$S.N;e.4B();e.$G=e.$k.17(".d-1K");e.$K=e.$k.17(".d-1p");e.3u="U";e.13=0;e.26=[0];e.m=0;e.4A();e.4z()},4z:9(){h e=c;e.2V();e.2W();e.4t();e.30();e.4r();e.4q();e.2p();e.4o();7(e.6.2o!==b){e.4n(e.6.2o)}7(e.6.O===j){e.6.O=4Q}e.19();e.$k.17(".d-1p").z("4i","4h");7(!e.$k.2m(":3n")){e.3o()}l{e.$k.z("2u",1)}e.5O=b;e.2l();7(A e.6.3s==="9"){e.6.3s.R(c,[e.$k])}},2l:9(){h e=c;7(e.6.1Z===j){e.1Z()}7(e.6.1B===j){e.1B()}e.4g();7(A e.6.3w==="9"){e.6.3w.R(c,[e.$k])}},3x:9(){h e=c;7(A e.6.3B==="9"){e.6.3B.R(c,[e.$k])}e.3o();e.2V();e.2W();e.4f();e.30();e.2l();7(A e.6.3D==="9"){e.6.3D.R(c,[e.$k])}},3F:9(){h e=c;t.1c(9(){e.3x()},0)},3o:9(){h e=c;7(e.$k.2m(":3n")===b){e.$k.z({2u:0});t.18(e.1C);t.18(e.1X)}l{p b}e.1X=t.4d(9(){7(e.$k.2m(":3n")){e.3F();e.$k.4b({2u:1},2M);t.18(e.1X)}},5x)},4B:9(){h e=c;e.$S.5n(\'<L H="d-1p">\').4a(\'<L H="d-1K"></L>\');e.$k.17(".d-1p").4a(\'<L H="d-1p-49">\');e.1H=e.$k.17(".d-1p-49");e.$k.z("4i","4h")},1M:9(){h e=c,t=e.$k.1I(e.6.1M),n=e.$k.1I(e.6.2i);7(!t){e.$k.I(e.6.1M)}7(!n){e.$k.I(e.6.2i)}},2V:9(){h t=c,n,r;7(t.6.2Z===b){p b}7(t.6.48===j){t.6.q=t.2t=1;t.6.1h=b;t.6.1s=b;t.6.1O=b;t.6.22=b;t.6.1Q=b;t.6.1R=b;p b}n=e(t.6.47).1f();7(n>(t.6.1s[0]||t.2t)){t.6.q=t.2t}7(t.6.1h!==b){t.6.1h.5g(9(e,t){p e[0]-t[0]});1A(r=0;r<t.6.1h.N;r+=1){7(t.6.1h[r][0]<=n){t.6.q=t.6.1h[r][1]}}}l{7(n<=t.6.1s[0]&&t.6.1s!==b){t.6.q=t.6.1s[1]}7(n<=t.6.1O[0]&&t.6.1O!==b){t.6.q=t.6.1O[1]}7(n<=t.6.22[0]&&t.6.22!==b){t.6.q=t.6.22[1]}7(n<=t.6.1Q[0]&&t.6.1Q!==b){t.6.q=t.6.1Q[1]}7(n<=t.6.1R[0]&&t.6.1R!==b){t.6.q=t.6.1R[1]}}7(t.6.q>t.E&&t.6.46===j){t.6.q=t.E}},4r:9(){h n=c,r,i;7(n.6.2Z!==j){p b}i=e(t).1f();n.3d=9(){7(e(t).1f()!==i){7(n.6.O!==b){t.18(n.1C)}t.5d(r);r=t.1c(9(){i=e(t).1f();n.3x()},n.6.45)}};e(t).44(n.3d)},4f:9(){h e=c;e.2g(e.m);7(e.6.O!==b){e.3j()}},43:9(){h t=c,n=0,r=t.E-t.6.q;t.$G.2f(9(i){h s=e(c);s.z({1f:t.M}).v("d-1K",3p(i));7(i%t.6.q===0||i===r){7(!(i>r)){n+=1}}s.v("d-24",n)})},42:9(){h e=c,t=e.$G.N*e.M;e.$K.z({1f:t*2,T:0});e.43()},2W:9(){h e=c;e.40();e.42();e.3Z();e.3v()},40:9(){h e=c;e.M=1F.4O(e.$k.1f()/e.6.q)},3v:9(){h e=c,t=(e.E*e.M-e.6.q*e.M)*-1;7(e.6.q>e.E){e.D=0;t=0;e.3z=0}l{e.D=e.E-e.6.q;e.3z=t}p t},3Y:9(){p 0},3Z:9(){h t=c,n=0,r=0,i,s,o;t.J=[0];t.3E=[];1A(i=0;i<t.E;i+=1){r+=t.M;t.J.2D(-r);7(t.6.12===j){s=e(t.$G[i]);o=s.v("d-24");7(o!==n){t.3E[n]=t.J[i];n=o}}}},4t:9(){h t=c;7(t.6.2a===j||t.6.1v===j){t.B=e(\'<L H="d-5A"/>\').5m("5l",!t.F.15).5c(t.$k)}7(t.6.1v===j){t.3T()}7(t.6.2a===j){t.3S()}},3S:9(){h t=c,n=e(\'<L H="d-4U"/>\');t.B.1o(n);t.1u=e("<L/>",{"H":"d-1n",2y:t.6.2U[0]||""});t.1q=e("<L/>",{"H":"d-U",2y:t.6.2U[1]||""});n.1o(t.1u).1o(t.1q);n.w("2X.B 21.B",\'L[H^="d"]\',9(e){e.1l()});n.w("2n.B 28.B",\'L[H^="d"]\',9(n){n.1l();7(e(c).1I("d-U")){t.U()}l{t.1n()}})},3T:9(){h t=c;t.1k=e(\'<L H="d-1v"/>\');t.B.1o(t.1k);t.1k.w("2n.B 28.B",".d-1j",9(n){n.1l();7(3p(e(c).v("d-1j"))!==t.m){t.1g(3p(e(c).v("d-1j")),j)}})},3P:9(){h t=c,n,r,i,s,o,u;7(t.6.1v===b){p b}t.1k.2y("");n=0;r=t.E-t.E%t.6.q;1A(s=0;s<t.E;s+=1){7(s%t.6.q===0){n+=1;7(r===s){i=t.E-t.6.q}o=e("<L/>",{"H":"d-1j"});u=e("<3N></3N>",{4R:t.6.39===j?n:"","H":t.6.39===j?"d-59":""});o.1o(u);o.v("d-1j",r===s?i:s);o.v("d-24",n);t.1k.1o(o)}}t.35()},35:9(){h t=c;7(t.6.1v===b){p b}t.1k.17(".d-1j").2f(9(){7(e(c).v("d-24")===e(t.$G[t.m]).v("d-24")){t.1k.17(".d-1j").Z("2d");e(c).I("2d")}})},3e:9(){h e=c;7(e.6.2a===b){p b}7(e.6.2e===b){7(e.m===0&&e.D===0){e.1u.I("1b");e.1q.I("1b")}l 7(e.m===0&&e.D!==0){e.1u.I("1b");e.1q.Z("1b")}l 7(e.m===e.D){e.1u.Z("1b");e.1q.I("1b")}l 7(e.m!==0&&e.m!==e.D){e.1u.Z("1b");e.1q.Z("1b")}}},30:9(){h e=c;e.3P();e.3e();7(e.B){7(e.6.q>=e.E){e.B.3K()}l{e.B.3J()}}},55:9(){h e=c;7(e.B){e.B.3k()}},U:9(e){h t=c;7(t.1E){p b}t.m+=t.6.12===j?t.6.q:1;7(t.m>t.D+(t.6.12===j?t.6.q-1:0)){7(t.6.2e===j){t.m=0;e="2k"}l{t.m=t.D;p b}}t.1g(t.m,e)},1n:9(e){h t=c;7(t.1E){p b}7(t.6.12===j&&t.m>0&&t.m<t.6.q){t.m=0}l{t.m-=t.6.12===j?t.6.q:1}7(t.m<0){7(t.6.2e===j){t.m=t.D;e="2k"}l{t.m=0;p b}}t.1g(t.m,e)},1g:9(e,n,r){h i=c,s;7(i.1E){p b}7(A i.6.1Y==="9"){i.6.1Y.R(c,[i.$k])}7(e>=i.D){e=i.D}l 7(e<=0){e=0}i.m=i.d.m=e;7(i.6.2o!==b&&r!=="4e"&&i.6.q===1&&i.F.1x===j){i.1t(0);7(i.F.1x===j){i.1L(i.J[e])}l{i.1r(i.J[e],1)}i.2r();i.4l();p b}s=i.J[e];7(i.F.1x===j){i.1T=b;7(n===j){i.1t("1w");t.1c(9(){i.1T=j},i.6.1w)}l 7(n==="2k"){i.1t(i.6.2v);t.1c(9(){i.1T=j},i.6.2v)}l{i.1t("1m");t.1c(9(){i.1T=j},i.6.1m)}i.1L(s)}l{7(n===j){i.1r(s,i.6.1w)}l 7(n==="2k"){i.1r(s,i.6.2v)}l{i.1r(s,i.6.1m)}}i.2r()},2g:9(e){h t=c;7(A t.6.1Y==="9"){t.6.1Y.R(c,[t.$k])}7(e>=t.D||e===-1){e=t.D}l 7(e<=0){e=0}t.1t(0);7(t.F.1x===j){t.1L(t.J[e])}l{t.1r(t.J[e],1)}t.m=t.d.m=e;t.2r()},2r:9(){h e=c;e.26.2D(e.m);e.13=e.d.13=e.26[e.26.N-2];e.26.5f(0);7(e.13!==e.m){e.35();e.3e();e.2l();7(e.6.O!==b){e.3j()}}7(A e.6.3y==="9"&&e.13!==e.m){e.6.3y.R(c,[e.$k])}},X:9(){h e=c;e.3A="X";t.18(e.1C)},3j:9(){h e=c;7(e.3A!=="X"){e.19()}},19:9(){h e=c;e.3A="19";7(e.6.O===b){p b}t.18(e.1C);e.1C=t.4d(9(){e.U(j)},e.6.O)},1t:9(e){h t=c;7(e==="1m"){t.$K.z(t.2z(t.6.1m))}l 7(e==="1w"){t.$K.z(t.2z(t.6.1w))}l 7(A e!=="2Y"){t.$K.z(t.2z(e))}},2z:9(e){p{"-1G-1a":"2C "+e+"1z 2s","-1W-1a":"2C "+e+"1z 2s","-o-1a":"2C "+e+"1z 2s",1a:"2C "+e+"1z 2s"}},3H:9(){p{"-1G-1a":"","-1W-1a":"","-o-1a":"",1a:""}},3I:9(e){p{"-1G-P":"1i("+e+"V, C, C)","-1W-P":"1i("+e+"V, C, C)","-o-P":"1i("+e+"V, C, C)","-1z-P":"1i("+e+"V, C, C)",P:"1i("+e+"V, C,C)"}},1L:9(e){h t=c;t.$K.z(t.3I(e))},3L:9(e){h t=c;t.$K.z({T:e})},1r:9(e,t){h n=c;n.29=b;n.$K.X(j,j).4b({T:e},{54:t||n.6.1m,3M:9(){n.29=j}})},4E:9(){h e=c,r="1i(C, C, C)",i=n.56("L"),s,o,u,a;i.2w.3O="  -1W-P:"+r+"; -1z-P:"+r+"; -o-P:"+r+"; -1G-P:"+r+"; P:"+r;s=/1i\\(C, C, C\\)/g;o=i.2w.3O.5i(s);u=o!==14&&o.N===1;a="5z"38 t||t.5Q.4P;e.F={1x:u,15:a}},4q:9(){h e=c;7(e.6.27!==b||e.6.1U!==b){e.3Q();e.3R()}},4C:9(){h e=c,t=["s","e","x"];e.16={};7(e.6.27===j&&e.6.1U===j){t=["2X.d 21.d","2N.d 3U.d","2n.d 3V.d 28.d"]}l 7(e.6.27===b&&e.6.1U===j){t=["2X.d","2N.d","2n.d 3V.d"]}l 7(e.6.27===j&&e.6.1U===b){t=["21.d","3U.d","28.d"]}e.16.3W=t[0];e.16.2K=t[1];e.16.2J=t[2]},3R:9(){h t=c;t.$k.w("5y.d",9(e){e.1l()});t.$k.w("21.3X",9(t){p e(t.1d).2m("5C, 5E, 5F, 5N")})},3Q:9(){9 s(e){7(e.2b!==W){p{x:e.2b[0].2c,y:e.2b[0].41}}7(e.2b===W){7(e.2c!==W){p{x:e.2c,y:e.41}}7(e.2c===W){p{x:e.52,y:e.53}}}}9 o(t){7(t==="w"){e(n).w(r.16.2K,a);e(n).w(r.16.2J,f)}l 7(t==="Q"){e(n).Q(r.16.2K);e(n).Q(r.16.2J)}}9 u(n){h u=n.3h||n||t.3g,a;7(u.5a===3){p b}7(r.E<=r.6.q){p}7(r.29===b&&!r.6.3f){p b}7(r.1T===b&&!r.6.3f){p b}7(r.6.O!==b){t.18(r.1C)}7(r.F.15!==j&&!r.$K.1I("3b")){r.$K.I("3b")}r.11=0;r.Y=0;e(c).z(r.3H());a=e(c).2h();i.2S=a.T;i.2R=s(u).x-a.T;i.2P=s(u).y-a.5o;o("w");i.2j=b;i.2L=u.1d||u.4c}9 a(o){h u=o.3h||o||t.3g,a,f;r.11=s(u).x-i.2R;r.2I=s(u).y-i.2P;r.Y=r.11-i.2S;7(A r.6.2E==="9"&&i.3C!==j&&r.Y!==0){i.3C=j;r.6.2E.R(r,[r.$k])}7((r.Y>8||r.Y<-8)&&r.F.15===j){7(u.1l!==W){u.1l()}l{u.5L=b}i.2j=j}7((r.2I>10||r.2I<-10)&&i.2j===b){e(n).Q("2N.d")}a=9(){p r.Y/5};f=9(){p r.3z+r.Y/5};r.11=1F.3v(1F.3Y(r.11,a()),f());7(r.F.1x===j){r.1L(r.11)}l{r.3L(r.11)}}9 f(n){h s=n.3h||n||t.3g,u,a,f;s.1d=s.1d||s.4c;i.3C=b;7(r.F.15!==j){r.$K.Z("3b")}7(r.Y<0){r.1y=r.d.1y="T"}l{r.1y=r.d.1y="3i"}7(r.Y!==0){u=r.4j();r.1g(u,b,"4e");7(i.2L===s.1d&&r.F.15!==j){e(s.1d).w("3a.4k",9(t){t.4S();t.4T();t.1l();e(t.1d).Q("3a.4k")});a=e.4N(s.1d,"4V").3a;f=a.4W();a.4X(0,0,f)}}o("Q")}h r=c,i={2R:0,2P:0,4Y:0,2S:0,2h:14,4Z:14,50:14,2j:14,51:14,2L:14};r.29=j;r.$k.w(r.16.3W,".d-1p",u)},4j:9(){h e=c,t=e.4m();7(t>e.D){e.m=e.D;t=e.D}l 7(e.11>=0){t=0;e.m=0}p t},4m:9(){h t=c,n=t.6.12===j?t.3E:t.J,r=t.11,i=14;e.2f(n,9(s,o){7(r-t.M/20>n[s+1]&&r-t.M/20<o&&t.34()==="T"){i=o;7(t.6.12===j){t.m=e.4p(i,t.J)}l{t.m=s}}l 7(r+t.M/20<o&&r+t.M/20>(n[s+1]||n[s]-t.M)&&t.34()==="3i"){7(t.6.12===j){i=n[s+1]||n[n.N-1];t.m=e.4p(i,t.J)}l{i=n[s+1];t.m=s+1}}});p t.m},34:9(){h e=c,t;7(e.Y<0){t="3i";e.3u="U"}l{t="T";e.3u="1n"}p t},4A:9(){h e=c;e.$k.w("d.U",9(){e.U()});e.$k.w("d.1n",9(){e.1n()});e.$k.w("d.19",9(t,n){e.6.O=n;e.19();e.32="19"});e.$k.w("d.X",9(){e.X();e.32="X"});e.$k.w("d.1g",9(t,n){e.1g(n)});e.$k.w("d.2g",9(t,n){e.2g(n)})},2p:9(){h e=c;7(e.6.2p===j&&e.F.15!==j&&e.6.O!==b){e.$k.w("57",9(){e.X()});e.$k.w("58",9(){7(e.32!=="X"){e.19()}})}},1Z:9(){h t=c,n,r,i,s,o;7(t.6.1Z===b){p b}1A(n=0;n<t.E;n+=1){r=e(t.$G[n]);7(r.v("d-1e")==="1e"){4s}i=r.v("d-1K");s=r.17(".5b");7(A s.v("1J")!=="2Y"){r.v("d-1e","1e");4s}7(r.v("d-1e")===W){s.3K();r.I("4u").v("d-1e","5e")}7(t.6.4v===j){o=i>=t.m}l{o=j}7(o&&i<t.m+t.6.q&&s.N){t.4w(r,s)}}},4w:9(e,n){9 o(){e.v("d-1e","1e").Z("4u");n.5h("v-1J");7(r.6.4x==="4y"){n.5j(5k)}l{n.3J()}7(A r.6.2T==="9"){r.6.2T.R(c,[r.$k])}}9 u(){i+=1;7(r.2Q(n.3l(0))||s===j){o()}l 7(i<=2q){t.1c(u,2q)}l{o()}}h r=c,i=0,s;7(n.5p("5q")==="5r"){n.z("5s-5t","5u("+n.v("1J")+")");s=j}l{n[0].1J=n.v("1J")}u()},1B:9(){9 s(){h r=e(n.$G[n.m]).2G();n.1H.z("2G",r+"V");7(!n.1H.1I("1B")){t.1c(9(){n.1H.I("1B")},0)}}9 o(){i+=1;7(n.2Q(r.3l(0))){s()}l 7(i<=2q){t.1c(o,2q)}l{n.1H.z("2G","")}}h n=c,r=e(n.$G[n.m]).17("5w"),i;7(r.3l(0)!==W){i=0;o()}l{s()}},2Q:9(e){h t;7(!e.3M){p b}t=A e.4D;7(t!=="W"&&e.4D===0){p b}p j},4g:9(){h t=c,n;7(t.6.2F===j){t.$G.Z("2d")}t.1D=[];1A(n=t.m;n<t.m+t.6.q;n+=1){t.1D.2D(n);7(t.6.2F===j){e(t.$G[n]).I("2d")}}t.d.1D=t.1D},4n:9(e){h t=c;t.4G="d-"+e+"-5B";t.4H="d-"+e+"-38"},4l:9(){9 a(e){p{2h:"5D",T:e+"V"}}h e=c,t=e.4G,n=e.4H,r=e.$G.1S(e.m),i=e.$G.1S(e.13),s=1F.4J(e.J[e.m])+e.J[e.13],o=1F.4J(e.J[e.m])+e.M/2,u="5G 5H 5I 5J";e.1E=j;e.$K.I("d-1P").z({"-1G-P-1P":o+"V","-1W-4K-1P":o+"V","4K-1P":o+"V"});i.z(a(s,10)).I(t).w(u,9(){e.3m=j;i.Q(u);e.31(i,t)});r.I(n).w(u,9(){e.36=j;r.Q(u);e.31(r,n)})},31:9(e,t){h n=c;e.z({2h:"",T:""}).Z(t);7(n.3m&&n.36){n.$K.Z("d-1P");n.3m=b;n.36=b;n.1E=b}},4o:9(){h e=c;e.d={2A:e.2A,5P:e.$k,S:e.$S,G:e.$G,m:e.m,13:e.13,1D:e.1D,15:e.F.15,F:e.F,1y:e.1y}},3G:9(){h r=c;r.$k.Q(".d d 21.3X");e(n).Q(".d d");e(t).Q("44",r.3d)},1V:9(){h e=c;7(e.$k.25().N!==0){e.$K.3r();e.$S.3r().3r();7(e.B){e.B.3k()}}e.3G();e.$k.2x("2w",e.$k.v("d-4I")||"").2x("H",e.$k.v("d-4F"))},5T:9(){h e=c;e.X();t.18(e.1X);e.1V();e.$k.5U()},5V:9(t){h n=c,r=e.4M({},n.2A,t);n.1V();n.1N(r,n.$k)},5W:9(e,t){h n=c,r;7(!e){p b}7(n.$k.25().N===0){n.$k.1o(e);n.23();p b}n.1V();7(t===W||t===-1){r=-1}l{r=t}7(r>=n.$S.N||r===-1){n.$S.1S(-1).5X(e)}l{n.$S.1S(r).5Y(e)}n.23()},5Z:9(e){h t=c,n;7(t.$k.25().N===0){p b}7(e===W||e===-1){n=-1}l{n=e}t.1V();t.$S.1S(n).3k();t.23()}};e.37.2B=9(t){p c.2f(9(){7(e(c).v("d-1N")===j){p b}e(c).v("d-1N",j);h n=3c.3q(r);n.1N(t,c);e.v(c,"2B",n)})};e.37.2B.6={q:5,1h:b,1s:[60,4],1O:[61,3],22:[62,2],1Q:b,1R:[63,1],48:b,46:b,1m:2M,1w:64,2v:65,O:b,2p:b,2a:b,2U:["1n","U"],2e:j,12:b,1v:j,39:b,2Z:j,45:2M,47:t,1M:"d-66",2i:"d-2i",1Z:b,4v:j,4x:"4y",1B:b,2O:b,33:b,3f:j,27:j,1U:j,2F:b,2o:b,3B:b,3D:b,2H:b,3s:b,1Y:b,3y:b,3w:b,2E:b,2T:b}})(67,68,69)',62,382,'||||||options|if||function||false|this|owl||||var||true|elem|else|currentItem|||return|items|||||data|on|||css|typeof|owlControls|0px|maximumItem|itemsAmount|browser|owlItems|class|addClass|positionsInArray|owlWrapper|div|itemWidth|length|autoPlay|transform|off|apply|userItems|left|next|px|undefined|stop|newRelativeX|removeClass||newPosX|scrollPerPage|prevItem|null|isTouch|ev_types|find|clearInterval|play|transition|disabled|setTimeout|target|loaded|width|goTo|itemsCustom|translate3d|page|paginationWrapper|preventDefault|slideSpeed|prev|append|wrapper|buttonNext|css2slide|itemsDesktop|swapSpeed|buttonPrev|pagination|paginationSpeed|support3d|dragDirection|ms|for|autoHeight|autoPlayInterval|visibleItems|isTransition|Math|webkit|wrapperOuter|hasClass|src|item|transition3d|baseClass|init|itemsDesktopSmall|origin|itemsTabletSmall|itemsMobile|eq|isCss3Finish|touchDrag|unWrap|moz|checkVisible|beforeMove|lazyLoad||mousedown|itemsTablet|setVars|roundPages|children|prevArr|mouseDrag|mouseup|isCssFinish|navigation|touches|pageX|active|rewindNav|each|jumpTo|position|theme|sliding|rewind|eachMoveUpdate|is|touchend|transitionStyle|stopOnHover|100|afterGo|ease|orignalItems|opacity|rewindSpeed|style|attr|html|addCssSpeed|userOptions|owlCarousel|all|push|startDragging|addClassActive|height|beforeInit|newPosY|end|move|targetElement|200|touchmove|jsonPath|offsetY|completeImg|offsetX|relativePos|afterLazyLoad|navigationText|updateItems|calculateAll|touchstart|string|responsive|updateControls|clearTransStyle|hoverStatus|jsonSuccess|moveDirection|checkPagination|endCurrent|fn|in|paginationNumbers|click|grabbing|Object|resizer|checkNavigation|dragBeforeAnimFinish|event|originalEvent|right|checkAp|remove|get|endPrev|visible|watchVisibility|Number|create|unwrap|afterInit|logIn|playDirection|max|afterAction|updateVars|afterMove|maximumPixels|apStatus|beforeUpdate|dragging|afterUpdate|pagesInArray|reload|clearEvents|removeTransition|doTranslate|show|hide|css2move|complete|span|cssText|updatePagination|gestures|disabledEvents|buildButtons|buildPagination|mousemove|touchcancel|start|disableTextSelect|min|loops|calculateWidth|pageY|appendWrapperSizes|appendItemsSizes|resize|responsiveRefreshRate|itemsScaleUp|responsiveBaseWidth|singleItem|outer|wrap|animate|srcElement|setInterval|drag|updatePosition|onVisibleItems|block|display|getNewPosition|disable|singleItemTransition|closestItem|transitionTypes|owlStatus|inArray|moveEvents|response|continue|buildControls|loading|lazyFollow|lazyPreload|lazyEffect|fade|onStartup|customEvents|wrapItems|eventTypes|naturalWidth|checkBrowser|originalClasses|outClass|inClass|originalStyles|abs|perspective|loadContent|extend|_data|round|msMaxTouchPoints|5e3|text|stopImmediatePropagation|stopPropagation|buttons|events|pop|splice|baseElWidth|minSwipe|maxSwipe|dargging|clientX|clientY|duration|destroyControls|createElement|mouseover|mouseout|numbers|which|lazyOwl|appendTo|clearTimeout|checked|shift|sort|removeAttr|match|fadeIn|400|clickable|toggleClass|wrapAll|top|prop|tagName|DIV|background|image|url|wrapperWidth|img|500|dragstart|ontouchstart|controls|out|input|relative|textarea|select|webkitAnimationEnd|oAnimationEnd|MSAnimationEnd|animationend|getJSON|returnValue|hasOwnProperty|option|onstartup|baseElement|navigator|new|prototype|destroy|removeData|reinit|addItem|after|before|removeItem|1199|979|768|479|800|1e3|carousel|jQuery|window|document'.split('|'),0,{}))
/*! respimage - v1.4.1 - 2015-06-09
 Licensed MIT */
!function(a,b,c){"use strict";function d(a){return a.trim?a.trim():a.replace(/^\s+|\s+$/g,"")}function e(){var b;R=!1,U=a.devicePixelRatio,S={},T={},b=(U||1)*D.xQuant,D.uT||(D.maxX=Math.max(1.3,D.maxX),b=Math.min(b,D.maxX),v.DPR=b),V.width=Math.max(a.innerWidth||0,B.clientWidth),V.height=Math.max(a.innerHeight||0,B.clientHeight),V.vw=V.width/100,V.vh=V.height/100,V.em=v.getEmValue(),V.rem=V.em,o=D.lazyFactor/2,o=o*b+o,q=.4+.1*b,l=.5+.2*b,m=.5+.25*b,p=b+1.3,(n=V.width>V.height)||(o*=.9),I&&(o*=.9),u=[V.width,V.height,b].join("-")}function f(a,b,c){var d=b*Math.pow(a-.4,1.9);return n||(d/=1.3),a+=d,a>c}function g(a){var b,c=v.getSet(a),d=!1;"pending"!=c&&(d=u,c&&(b=v.setRes(c),d=v.applySetCandidate(b,a))),a[v.ns].evaled=d}function h(a,b){return a.res-b.res}function i(a,b,c){var d;return!c&&b&&(c=a[v.ns].sets,c=c&&c[c.length-1]),d=j(b,c),d&&(b=v.makeUrl(b),a[v.ns].curSrc=b,a[v.ns].curCan=d,d.res||_(d,d.set.sizes)),d}function j(a,b){var c,d,e;if(a&&b)for(e=v.parseSet(b),a=v.makeUrl(a),c=0;c<e.length;c++)if(a==v.makeUrl(e[c].url)){d=e[c];break}return d}function k(a,b){var c,d,e,f,g=a.getElementsByTagName("source");for(c=0,d=g.length;d>c;c++)e=g[c],e[v.ns]=!0,f=e.getAttribute("srcset"),f&&b.push({srcset:f,media:e.getAttribute("media"),type:e.getAttribute("type"),sizes:e.getAttribute("sizes")})}var l,m,n,o,p,q,r,s,t,u,v={},w=function(){},x=b.createElement("img"),y=x.getAttribute,z=x.setAttribute,A=x.removeAttribute,B=b.documentElement,C={},D={xQuant:1,lazyFactor:.4,maxX:2},E="data-risrc",F=E+"set",G="webkitBackfaceVisibility"in B.style,H=navigator.userAgent,I=/rident/.test(H)||/ecko/.test(H)&&H.match(/rv\:(\d+)/)&&RegExp.$1>35,J="currentSrc",K=/\s+\+?\d+(e\d+)?w/,L=/((?:\([^)]+\)(?:\s*and\s*|\s*or\s*|\s*not\s*)?)+)?\s*(.+)/,M=/^([\+eE\d\.]+)(w|x)$/,N=/\s*\d+h\s*/,O=a.respimgCFG,P=("https:"==location.protocol,"position:absolute;left:0;visibility:hidden;display:block;padding:0;border:none;font-size:1em;width:1em;overflow:hidden;clip:rect(0px, 0px, 0px, 0px)"),Q="font-size:100%!important;",R=!0,S={},T={},U=a.devicePixelRatio,V={px:1,"in":96},W=b.createElement("a"),X=!1,Y=function(a,b,c,d){a.addEventListener?a.addEventListener(b,c,d||!1):a.attachEvent&&a.attachEvent("on"+b,c)},Z=function(a){var b={};return function(c){return c in b||(b[c]=a(c)),b[c]}},$=function(){var a=/^([\d\.]+)(em|vw|px)$/,b=function(){for(var a=arguments,b=0,c=a[0];++b in a;)c=c.replace(a[b],a[++b]);return c},c=Z(function(a){return"return "+b((a||"").toLowerCase(),/\band\b/g,"&&",/,/g,"||",/min-([a-z-\s]+):/g,"e.$1>=",/max-([a-z-\s]+):/g,"e.$1<=",/calc([^)]+)/g,"($1)",/(\d+[\.]*[\d]*)([a-z]+)/g,"($1 * e.$2)",/^(?!(e.[a-z]|[0-9\.&=|><\+\-\*\(\)\/])).*/gi,"")});return function(b,d){var e;if(!(b in S))if(S[b]=!1,d&&(e=b.match(a)))S[b]=e[1]*V[e[2]];else try{S[b]=new Function("e",c(b))(V)}catch(f){}return S[b]}}(),_=function(a,b){return a.w?(a.cWidth=v.calcListLength(b||"100vw"),a.res=a.w/a.cWidth):a.res=a.x,a},ab=function(c){var d,e,f,g=c||{};if(g.elements&&1==g.elements.nodeType&&("IMG"==g.elements.nodeName.toUpperCase()?g.elements=[g.elements]:(g.context=g.elements,g.elements=null)),g.reparse&&(g.reevaluate=!0,a.console&&console.warn&&console.warn("reparse was renamed to reevaluate!")),d=g.elements||v.qsa(g.context||b,g.reevaluate||g.reselect?v.sel:v.selShort),f=d.length){for(v.setupRun(g),X=!0,e=0;f>e;e++)v.fillImg(d[e],g);v.teardownRun(g)}},bb=Z(function(a){var b=[1,"x"],c=d(a||"");return c&&(c=c.replace(N,""),b=c.match(M)?[1*RegExp.$1,RegExp.$2]:!1),b});J in x||(J="src"),C["image/jpeg"]=!0,C["image/gif"]=!0,C["image/png"]=!0,C["image/svg+xml"]=b.implementation.hasFeature("http://wwwindow.w3.org/TR/SVG11/feature#Image","1.1"),v.ns=("ri"+(new Date).getTime()).substr(0,9),v.supSrcset="srcset"in x,v.supSizes="sizes"in x,v.selShort="picture>img,img[srcset]",v.sel=v.selShort,v.cfg=D,v.supSrcset&&(v.sel+=",img["+F+"]"),v.DPR=U||1,v.u=V,v.types=C,s=v.supSrcset&&!v.supSizes,v.setSize=w,v.makeUrl=Z(function(a){return W.href=a,W.href}),v.qsa=function(a,b){return a.querySelectorAll(b)},v.matchesMedia=function(){return v.matchesMedia=a.matchMedia&&(matchMedia("(min-width: 0.1em)")||{}).matches?function(a){return!a||matchMedia(a).matches}:v.mMQ,v.matchesMedia.apply(this,arguments)},v.mMQ=function(a){return a?$(a):!0},v.calcLength=function(a){var b=$(a,!0)||!1;return 0>b&&(b=!1),b},v.supportsType=function(a){return a?C[a]:!0},v.parseSize=Z(function(a){var b=(a||"").match(L);return{media:b&&b[1],length:b&&b[2]}}),v.parseSet=function(a){if(!a.cands){var b,c,d,e,f,g,h=a.srcset;for(a.cands=[];h;)h=h.replace(/^\s+/g,""),b=h.search(/\s/g),d=null,-1!=b?(c=h.slice(0,b),e=c.charAt(c.length-1),","!=e&&c||(c=c.replace(/,+$/,""),d=""),h=h.slice(b+1),null==d&&(f=h.indexOf(","),-1!=f?(d=h.slice(0,f),h=h.slice(f+1)):(d=h,h=""))):(c=h,h=""),c&&(d=bb(d))&&(g={url:c.replace(/^,+/,""),set:a},g[d[1]]=d[0],"x"==d[1]&&1==d[0]&&(a.has1x=!0),a.cands.push(g))}return a.cands},v.getEmValue=function(){var a;if(!r&&(a=b.body)){var c=b.createElement("div"),d=B.style.cssText,e=a.style.cssText;c.style.cssText=P,B.style.cssText=Q,a.style.cssText=Q,a.appendChild(c),r=c.offsetWidth,a.removeChild(c),r=parseFloat(r,10),B.style.cssText=d,a.style.cssText=e}return r||16},v.calcListLength=function(a){if(!(a in T)||D.uT){var b,c,e,f,g,h,i=d(a).split(/\s*,\s*/),j=!1;for(g=0,h=i.length;h>g&&(b=i[g],c=v.parseSize(b),e=c.length,f=c.media,!e||!v.matchesMedia(f)||(j=v.calcLength(e))===!1);g++);T[a]=j?j:V.width}return T[a]},v.setRes=function(a){var b;if(a){b=v.parseSet(a);for(var c=0,d=b.length;d>c;c++)_(b[c],a.sizes)}return b},v.setRes.res=_,v.applySetCandidate=function(a,b){if(a.length){var c,d,e,g,j,k,n,r,s,t,w,x,y,z=b[v.ns],A=u,B=o,C=q;if(r=z.curSrc||b[J],s=z.curCan||i(b,r,a[0].set),d=v.DPR,y=s&&s.res,!n&&r&&(x=I&&!b.complete&&s&&y-.2>d,x||s&&!(p>y)||(s&&d>y&&y>l&&(m>y&&(B*=.8,C+=.04*d),s.res+=B*Math.pow(y-C,2)),t=!z.pic||s&&s.set==a[0].set,s&&t&&s.res>=d&&(n=s))),!n)for(y&&(s.res=s.res-(s.res-y)/2),a.sort(h),k=a.length,n=a[k-1],e=0;k>e;e++)if(c=a[e],c.res>=d){g=e-1,n=a[g]&&(j=c.res-d)&&(x||r!=v.makeUrl(c.url))&&f(a[g].res,j,d)?a[g]:c;break}return y&&(s.res=y),n&&(w=v.makeUrl(n.url),z.curSrc=w,z.curCan=n,w!=r&&v.setSrc(b,n),v.setSize(b)),A}},v.setSrc=function(a,b){var c;a.src=b.url,G&&(c=a.style.zoom,a.style.zoom="0.999",a.style.zoom=c)},v.getSet=function(a){var b,c,d,e=!1,f=a[v.ns].sets;for(b=0;b<f.length&&!e;b++)if(c=f[b],c.srcset&&v.matchesMedia(c.media)&&(d=v.supportsType(c.type))){"pending"==d&&(c=d),e=c;break}return e},v.parseSets=function(a,b,d){var e,f,g,h,i="PICTURE"==b.nodeName.toUpperCase(),l=a[v.ns];(l.src===c||d.src)&&(l.src=y.call(a,"src"),l.src?z.call(a,E,l.src):A.call(a,E)),(l.srcset===c||!v.supSrcset||a.srcset||d.srcset)&&(e=y.call(a,"srcset"),l.srcset=e,h=!0),l.sets=[],i&&(l.pic=!0,k(b,l.sets)),l.srcset?(f={srcset:l.srcset,sizes:y.call(a,"sizes")},l.sets.push(f),g=(s||l.src)&&K.test(l.srcset||""),g||!l.src||j(l.src,f)||f.has1x||(f.srcset+=", "+l.src,f.cands.push({url:l.src,x:1,set:f}))):l.src&&l.sets.push({srcset:l.src,sizes:null}),l.curCan=null,l.curSrc=c,l.supported=!(i||f&&!v.supSrcset||g),h&&v.supSrcset&&!l.supported&&(e?(z.call(a,F,e),a.srcset=""):A.call(a,F)),l.supported&&!l.srcset&&(!l.src&&a.src||a.src!=v.makeUrl(l.src))&&(null==l.src?a.removeAttribute("src"):a.src=l.src),l.parsed=!0},v.fillImg=function(a,b){var c,d,e=b.reselect||b.reevaluate;if(a[v.ns]||(a[v.ns]={}),d=a[v.ns],e||d.evaled!=u){if(!d.parsed||b.reevaluate){if(c=a.parentNode,!c)return;v.parseSets(a,c,b)}d.supported?d.evaled=u:g(a)}},v.setupRun=function(b){(!X||R||U!=a.devicePixelRatio)&&(e(),b.elements||b.context||clearTimeout(t))},a.HTMLPictureElement?(ab=w,v.fillImg=w):(b.createElement("picture"),function(){var c,d=a.attachEvent?/d$|^c/:/d$|^c|^i/,e=function(){var a=b.readyState||"";h=setTimeout(e,"loading"==a?200:999),b.body&&(c=c||d.test(a),v.fillImgs(),c&&clearTimeout(h))},f=function(){v.fillImgs()},g=function(){clearTimeout(t),R=!0,t=setTimeout(f,99)},h=setTimeout(e,b.body?0:20);Y(a,"resize",g),Y(b,"readystatechange",e)}()),v.respimage=ab,v.fillImgs=ab,v.teardownRun=w,ab._=v,a.respimage=ab,a.respimgCFG={ri:v,push:function(a){var b=a.shift();"function"==typeof v[b]?v[b].apply(v,a):(D[b]=a[0],X&&v.fillImgs({reselect:!0}))}};for(;O&&O.length;)a.respimgCFG.push(O.shift())}(window,document);
!function ($) {
		'use strict';

		$('.brands-carousel').each(function () {
				var $brand = $(this);

				$brand.owlCarousel({
						autoPlay: 3000,
						pagination: !!$brand.data('pagination'),
						items: 5,
						itemsDesktop: [1199, 4],
						itemsDesktopSmall: [991, 3],
						stopOnHover: true
				});
		});
}(jQuery);
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
!function ($) {

		$(function () {
				if (Modernizr.flexboxlegacy || !Modernizr.csstransforms) {
						return;
				}

				var $footer = $('.content-info'),
						$window = $(window),
						shift = 0,

						makeSticky = function () {
								var diff = $window.height() - $footer.offset().top - $footer.outerHeight(),
										transform = '';

								if (diff > 0 || shift > 0) {
										shift = Math.max(0, shift + diff);
										transform = 'translateY(' + shift + 'px)';
								}

								$footer.css('transform', transform);
						};

				if($footer.length) {
						makeSticky();
						setInterval(makeSticky, 500);
				}
		});
}(jQuery);
!function ($) {
		'use strict';

		var $window = $(window);

		function layout(data, instant, duration) {
				var count = 4,
						timer = data.timer,
						masonry = data.masonry;

				if (timer) {
						clearInterval(timer);
				}

				if (instant) {
						masonry.options.transitionDuration = duration !== undefined ? duration : '0.4s';
						masonry.layout();
				}

				timer = setInterval(function () {
						masonry.options.transitionDuration = 0;
						masonry.layout();
						count--;

						if (!count && timer) {
								clearInterval(timer);
						}
				}, 500);

				data.timer = timer;
		}

		function filter(data, value) {
				var masonry = data.masonry,
						$items = $(masonry.element).children('.grid-item'),
						$item,
						item,
						hide = [],
						reveal = [],
						index;

				for (index = 0; index < $items.length; index++) {
						$item = $items.eq(index);
						item = masonry.getItem($item.get(0));

						if (value === '*' || $.inArray(value, $item.data('filter').split(',')) >= 0) {
								if ($item.css('display') === 'none') reveal.push(item);
						} else {
								hide.push(item);
						}
				}
				masonry.hide(hide);
				masonry.reveal(reveal);
				//masonry.reloadItems();

				layout(data, true);
		}

		function onFilter(e) {
				var value = this.value,
						data = e.data,
						$select = data.$select;

				if ($select.val() !== value) $select.val(value);

				filter(data.data, value);
		}

		function onSelect(e) {
				e.data.$radios.filter('[value="' + this.value + '"]').parent().button('toggle');
		}

		$(function () {
				$('[data-om-grid]').each(function () {
						var $this = $(this),
								data,
								$filtersContainer = $this.prev('.grid-filters'),
								$radios = $filtersContainer.find('input'),
								$select = $filtersContainer.find('select'),
								timer,
								init = function () {
										if (timer) {
												clearTimeout(timer);
												timer = false;
										} else {
												return;
										}

										$this.masonry({
												columnWidth: '.grid-sizer',
												itemSelector: '.grid-item',
												percentPosition: true
										});

										data = $this.data();

										layout(data);

										$radios.on('change', { data: data, $select: $select }, onFilter);
										$select.on('change', { $radios: $radios }, onSelect);
										$window.on('resize', function () { layout(data, true, 0); });
								};

						timer = setTimeout(init, 1e4)
						$this.imagesLoaded(init);

						//$this.masonry();
				});
		});

}(jQuery);
!function ($) {
		'use strict';

		var notObjectFit = Modernizr && !Modernizr.prefixed('objectFit');

		function coverFallback() {
				var $img = $(this).css('opacity', 0);

				$('<div class="img-cover">').css({
						'background': 'url(' + $img.prop('src') + ') no-repeat center',
						'background-size': 'cover'
				}).insertAfter($img).append($img);
		}

		$(function () {
				if (notObjectFit) {
						$('[data-image-cover]').each(function () {
								var $this = $(this);

								$this.imagesLoaded(function () {
										$this.find('.img-cover').each(coverFallback);
								});
						});
				}
		});

}(jQuery);
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
!function ($) {
		'use strict';

		var photoswipe,
				photoswipeElement,
				$photoswipe,
				options = {
						captionEl: true,
						shareEl: false,
						fullscreenEl: false,
						zoomEl: false
				},
				dragTimer;

		function create() {
				var div = '<div>',
						span = '<span>',
						index,

						topBtns = [
								{ type: 'close', title: 'Close (Esc)', icon: 'ios-close' },
								{ type: 'share', title: 'Share', icon: 'android-share-alt' },
								{ type: 'fs', title: 'Toggle fullscreen', icon: 'ios-photos-outline' },
								{ type: 'zoom', title: 'Zoom in/out', icon: 'ios-plus-empty' }
						],
						btn,

						$photoswipe = $(div, { 'id': 'photoswipe', 'class': 'pswp', 'tabindex': '-1', 'role': 'dialog', 'aria-hidden': 'true' }),
						$wrap = $(div, { 'class': 'pswp__scroll-wrap' }).appendTo($photoswipe),
						$container = $(div, { 'class': 'pswp__container' }).appendTo($wrap),
						$ui = $(div, { 'class': 'pswp__ui pswp__ui--hidden' }).appendTo($wrap),
						$topBar = $(div, { 'class': 'pswp__top-bar' }).appendTo($ui),
						$shareModal = $(div, { 'class': 'pswp__share-modal pswp__share-modal--hidden pswp__single-tap' }).appendTo($ui),
						$caption = $(div, { 'class': 'pswp__caption' });

				$(div, { 'class': 'pswp__bg' }).prependTo($photoswipe);

				// container

				for (index = 0; index < 3; index++) {
						$('<div class="pswp__item">').appendTo($container);
				}

				// top bar

				$(div, { 'class': 'pswp__counter' }).prependTo($topBar);

				for (index = 0; index < topBtns.length; index++) {
						btn = topBtns[index];

						$('<button class="pswp__button">').addClass('pswp__button--' + btn.type + ' ion-' + btn.icon).attr('title', btn.title).appendTo($topBar);
				}

				$('<div class="pswp__preloader"><div class="pswp__preloader__icn"><div class="pswp__preloader__cut"><div class="pswp__preloader__donut"></div></div></div></div>').appendTo($topBar);

				//other ui

				$(div, { 'class': 'pswp__share-tooltip' }).prependTo($shareModal);

				$('<button class="pswp__button">').addClass('pswp__button--arrow--left').attr('title', 'Previous (arrow left)').append($(span).addClass('arrow-left')).appendTo($ui);
				$('<button class="pswp__button">').addClass('pswp__button--arrow--right').attr('title', 'Next (arrow right').append($(span).addClass('arrow-right')).appendTo($ui);

				$caption.appendTo($ui);

				$(div, { 'class': 'pswp__caption' }).appendTo($caption);

				return $photoswipe.appendTo('body');
		}

		function init() {
				if (!photoswipeElement) {
						var $meta = $('meta[name="photoswipe"]'),
								share = [
										{ id: 'facebook', label: '<span class="ion-social-facebook"></span>', url: 'https://www.facebook.com/sharer/sharer.php?u={{url}}' },
										{ id: 'twitter', label: '<span class="ion-social-twitter"></span>', url: 'https://twitter.com/intent/tweet?text={{text}}&url={{url}}' },
										{ id: 'pinterest', label: '<span class="ion-social-pinterest"></span>', url: 'http://www.pinterest.com/pin/create/button/?url={{url}}&media={{image_url}}&description={{text}}' },
										{ id: 'tumblr', label: '<span class="ion-social-tumblr"></span>', url: 'http://tumblr.com/widgets/share/tool?canonicalUrl={{url}}' },
										{ id: 'google-plus', label: '<span class="ion-social-googleplus"></span>', url: 'https://plus.google.com/share?url={{url}}' },
										{ id: 'vk', label: '<span class="ion-speakerphone"></span>', url: 'http://vk.com/share.php?url={{url}}' },
										{ id: 'reddit', label: '<span class="ion-social-reddit"></span>', url: 'https://www.reddit.com/submit?url={{url}}' },
										{ id: 'download', label: '<span class="ion-arrow-down-c"></span>', url: '{{raw_image_url}}', download: true }
								],
								item,
								meta,
								index;

						if ($meta.length && (meta = $meta.prop('content'))) {
								meta = $.parseJSON(meta);

								options.fullscreenEl = meta.fullscreen ? !!meta.fullscreen : false;
								options.zoomEl = meta.zoom ? !!meta.zoom : false;

								if ($.isPlainObject(meta.share)) {
										options.shareEl = true;
										options.shareButtons = [];

										for (index = 0; index < share.length; index++) {
												item = share[index];

												if (item.id in meta.share) {
														item.label += meta.share[item.id];
														options.shareButtons.push(item);
												}

										}
								}
						}

						$photoswipe = create();

						photoswipeElement = $photoswipe.get(0);
				}
		}

		function getTarget($item) {
				var selector = $item.data('selector'),
						$target = $item;

				if (selector) {
						selector = selector.split('|');
						$target = $item[selector[0]].apply($item, Array.prototype.slice.call(selector, 1));
				}

				return $target;
		}

		function itemToSlide (index, item) {
				var $item = $(item),
						size = $item.data('size').split('x'),
						width = parseInt(size[0]),
						height = parseInt(size[1]);

				$item.data('number', index);
				$item.data('ratio', width / height);

				return {
						src: $item.data('item'),
						w: width,
						h: height,
						title: $item.data('title')
				}
		}

		function show(uid, $items, $current) {
				var slides = $items.map(itemToSlide).get(),

						index = $current.data('number'),

						$target = getTarget($current),
						offset = $target.offset(),
						width = $target.width(),
						height = $target.height(),

						settings = $.extend({
								galleryUID: uid,
								index: index,
								getThumbBoundsFn: function () {
										return { x: offset.left, y: offset.top, w: width };
								},
								showHideOpacity: Math.abs(width / height - $current.data('ratio')) > 0.03
						}, options);

				photoswipe = new PhotoSwipe(photoswipeElement, PhotoSwipeUI_Default, slides, settings);

				photoswipe.init();

				photoswipe.listen('preventDragEvent', function (e, isDown, preventObj) {
						clearTimeout(dragTimer);

						if (isDown) {
								$photoswipe.addClass('pswp-dragging');
						} else {
								dragTimer = setTimeout(function () {
										$photoswipe.removeClass('pswp-dragging');
								}, 350);
						}
				});

				photoswipe.listen('beforeChange', function (e) {
						$photoswipe.removeClass('pswp-dragging');
				});
		}

		$(function () {
				$('[data-om-photoswipe]').each(function (uid) {
						var $gallery = $(this);

						init();

						$gallery.on('click', '[data-item]', function (event) {
								event.preventDefault();

								show(uid + 1, $gallery.find('[data-item]'), $(this));
						});
				});

				$(document)
						.on('click', '[data-photoswipe-group]', function (event) {
								event.preventDefault();

								init();

								var $this = $(this),
										group = $this.data('photoswipe-group');

								show('group-' + group, $('[data-photoswipe-group="' + group + '"]'), $this);
						})
						.on('click', '.pswp__button--arrow--left,.pswp__button--arrow--right', function (event) {
								var $target = $(event.target);

								if (!$target.hasClass('.pswp__button')) {
										$target.parent('.pswp__button').trigger('click');
								}
						});

				var $window = $(window),
						hash = location.hash,
						matches,
						uid,
						image,
						$images,
						$current;

				if (hash) {
						matches = hash.replace('#', '').match(/&gid=([^&]+)&pid=([^&]+)/)

						if ($.isArray(matches) && matches.length === 3) {
								uid = matches[1];
								image = matches[2];

								if (/^group-/.test(uid)) {
										$images = $('[data-photoswipe-group="' + uid.replace('group-', '') + '"]');
								} else if ($.isNumeric(uid) && uid >= 1) {
										uid = parseInt(uid);

										$images = $('[data-om-photoswipe]:eq(' + (uid - 1) + ') [data-item]');
								}

								if ($images && $images.length >= image) {
										init();

										$current = $images.eq(image - 1);

										$window.scrollTop($current.offset().top - $window.height() / 3);

										show(uid, $images, $current);
								}
						}
				}
		});

}(jQuery);
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
!function ($) {
		'use strict';

		var $document = $(document),
				$body = $('body'),
				location = window.location,
				hideTimer,
				url = location.origin + location.pathname,
				$splash = $('[data-om-splash]');

		var loader = new SVGLoader( document.getElementById( 'loader' ), { speedIn : 1200, easingIn : mina.easeinout } );
		// loader.show();
	
		function isCurrentPage($link) {
				var href = $link.prop('href');

				return $link.data('toggle') || !href || href.replace(url, '')[0] === '#';
		}

		function hideSplash() {
				$body.addClass('load'); 
				// clearTimeout(hideTimer);

				// var mm_anim = $("#harbor-splash-icon");
				// mm_anim.attr("style", "display:block");

				// $splash
				// 	.delay(2000)
				// 	.delayed(function () { $body.addClass('load'); })
				// 	.delayed(function () { loader.hide(); })
				// 	.delay(2000)
				// 	.delayed('addClass', 'loaded')
				// 	.delay($splash.data('omSplash') || 3000)
				// 	.delayed('addClass', 'hidden');
		}

		function showSplash(e) {
				// clearTimeout(hideTimer);


				// var $target = $(e.currentTarget);

				// if (isCurrentPage($target)) {
				// 		return;
				// }

				// if ($target.parents('.navmenu-nav').length) {
				// 		$('.navmenu-toggle').trigger('click');
				// }

				// $body.removeClass('load');

				// $splash
				// 		.clearQueue()
				// 		.removeClass('hidden')
				// 		.delay(50)
				// 		.delayed('removeClass', 'loaded');

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
!function ($) {
		'use strict';

		var $body = $('body');

		// Add to cart
		// ===========

		function updateQuantity(delta, dataName) {
				return function (index, element) {
						var $quantity = $(element),
								quantity = ($quantity.data(dataName) || 0) + delta;

						$quantity.data(dataName, quantity);

						$quantity
								.removeClass('added')
								.text(quantity)
								.delay(50)
								.delayed('addClass', 'added');


				}
		}

		$body
				.on('adding_to_cart', function (event, $button) {
						$button
								.clearQueue()
								.removeClass('add-success');
				})
				.on('added_to_cart', function (event, fragments, cart_hash, $button) {
						var productId = $button.data('product_id'),
								quantity = $button.data('quantity') || 1;

						$button
								.addClass('add-success')
								.delay(2000)
								.delayed('removeClass', 'add-success');

						$button.next('.added_to_cart').remove();

						$('[data-product-quantity][data-product=' + productId + ']').each(updateQuantity(quantity, 'productQuantity'));
						$('[data-wc-cart-count]').each(updateQuantity(quantity, 'wcCartCount'));
				})
				.on('click', '[data-toggle=quantity]', function (e) {
						var $this = $(this),
								data = $this.data(),
								$target,
								max,
								min,
								step,
								value;

						if (!data.$target) {
								data.$target = $this.parent().siblings('[data-quantity]');
						}

						$target = data.$target;
						max = parseFloat($target.prop('max')) || false;
						min = parseFloat($target.prop('min')) || 0;
						step = parseFloat($target.prop('step')) || 1;
						value = (parseFloat($target.val()) || 0) + (parseFloat(data.value) || 0) * step;

						if (max && value > max) {
								value = max;
						} else if (value < min) {
								value = min;
						}

						$target.val(value);

						// Trigger change event
						$target.trigger('change');
				});

		// Variations
		// ==========

		function updateVariationData($image, data) {
				var $wrapper = $image.parent(),
						slider = $wrapper.data('slider');

				data = data ? data : $wrapper.data('defaultVariation');

				$wrapper.data('title', data.title);
				$wrapper.data('size', data.size);
				$wrapper.data('item', data.item);
				$wrapper.attr('href', data.item);

				if (!slider) {
						slider = $wrapper.parents('[data-om-slider]').data('owlCarousel');
						$wrapper.data('slider', slider);
				}

				slider.goTo(0);
		}

		$('.variations_form')
				.each(function () {
						var $form = $(this),
								$product = $form.closest('.product'),
								$images = $product.find('.images'),
								$photoswipe = $images.find('[data-om-photoswipe]'),
								$image = $images.find('img:eq(0)'),
								$wrapper = $image.parent();

						if (!$photoswipe.length) {
								return;
						}

						$wrapper.data('defaultVariation', $.extend({}, $wrapper.data()));

						$form.on('wc_variation_form', function () {
								$form
										.on('found_variation', function (event, variation) {
												updateVariationData($images.find('img:eq(0)'), {
														item: variation.image_link,
														title: variation.image_title,
														size: variation.image_link_width + 'x' + variation.image_link_height
												});
										})
										.on('reset_data', function () {
												updateVariationData($images.find('img:eq(0)'));
										});
						});
				})
				.each(function () {
						$(this).on('change', '.variations select', function () {
								var $this = $(this),
										action = ($this.val() ? 'add' : 'remove') + 'Class';

								$this[action]('option-selected');
						});
				});

		// Fix of dropdown product cat widget
		$('select.dropdown_product_cat').addClass('form-control');

		// Fix of price filter widget
		$('.price_slider_wrapper .button').removeClass('button').addClass('btn btn-sm btn-flat btn-default');

}(jQuery);