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