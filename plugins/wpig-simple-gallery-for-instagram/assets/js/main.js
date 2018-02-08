var jq_scrape = $.noConflict();
angular.module("octolooks", []).controller("options", ["$scope", "$compile", "$timeout", function(a, k, l) {
	$ = jq_scrape;
	a.pro = function() {
		var a = $("#pro");
		$(".pro select").prop("disabled", !0);
		$(document).on("click", ".pro", function() {
			a.modal({backdrop:"static"});
			return !1;
		});
	};
	a.submit = function(b) {
		a.form.$invalid && (b.preventDefault(), a.submitted = !0, $(".collapse:not(.in)").collapse("show"), l(function() {
			$("body").animate({scrollTop:$(".has-error:eq(0)").offset().top - 30}, 1000);
		}));
	};
	a.set_popover = function() {
		$('[data-toggle="popover"]').popover({trigger:"hover", container:".bootstrap", html:!0});
	};
	a.set_radio_value = function() {
		$('input[type="radio"]:not([name="scrape_type"]):not(:checked)').each(function() {
			$('input[type="radio"]:not([name="scrape_type"])[name="' + $(this).attr("name") + '"]:checked').length || $(this).prop("checked", function() {
				eval("a." + $(this).attr("ng-model") + " = '" + $(this).val() + "'");
				return !0;
			});
		});
		"feed" == a.model.scrape_type && (a.model.scrape_title_type = "feed", a.model.scrape_content_type = "feed", a.model.scrape_excerpt_type = "auto", a.model.scrape_tags_type = "custom", a.model.scrape_featured_type = "feed", a.model.scrape_date_type = "runtime");
	};
	a.set_template_tags = function() {
		$(document).on("click", ".input-tags .btn", function() {
			var a = 0, c, d, f = $(this).data("value");
			d = $(this).parent().prev().find('input[type="text"]');
			d.length || (d = $("textarea.wp-editor-area"), d.is(":hidden") && $("#scrapetemplate-html").click());
			d.focus();
			var e = document.activeElement;
			d = e.selectionStart || "0" == e.selectionStart ? "ff" : document.selection ? "ie" : !1;
			"ie" == d ? (e.focus(), c = document.selection.createRange(), c.moveStart("character", -e.value.length), a = c.text.length) : "ff" == d && (a = e.selectionStart);
			c = e.value.substring(0, a);
			var h = e.value.substring(a, e.value.length);
			e.value = c + f + h;
			a += f.length;
			"ie" == d ? (e.focus(), c = document.selection.createRange(), c.moveStart("character", -e.value.length), c.moveStart("character", a), c.moveEnd("character", 0), c.select()) : "ff" == d && (e.selectionStart = a, e.selectionEnd = a, e.focus());
		});
	};
	a.set_sidebar = function() {
		var a = $(".sidebar");
		a.width(a.parent().width()).addClass("fixed");
	};
	a.show_media_library = function(a) {
		var b = wp.media({multiple:!1, title:translate.media_library_title, library:{type:"image"}});
		b.on("select", function() {
			var c = $(a.target).closest(".input-group").find('input[type="text"]');
			c.val(b.state().get("selection").first().toJSON().id);
			eval("a." + $(c).attr("ng-model") + " = '" + $(c).val() + "'");
		});
		b.open();
	};
	a.set_custom_field_name_auto_complete = function() {
		var b = $('input[name^="scrape_custom_fields"][name*="[name]"]');
		b.data("autocomplete") && b.autocomplete("destroy");
		if (a.model.all_custom_fields[a.model.scrape_post_type]) {
			b.autocomplete({source:a.model.all_custom_fields[a.model.scrape_post_type], minLength:0}).on("focus", function() {
				0 === $(this).val().length && $(this).autocomplete("search");
			});
		} else {
			b.autocomplete({source:[]});
		}
	};
	a.add_field = function(b, c) {
		if ("custom_field" == c) {
			var d = (new Date).getTime();
			$(b.target).closest(".form-group").before("<div class=\"form-group\" ng-class=\"{'has-error' : form['scrape_custom_fields[" + d + "][value]'].$invalid && (form['scrape_custom_fields[" + d + '][value]\'].$dirty || submitted)}"><div class="col-sm-12"><div class="input-group"><div class="input-group-addon">' + translate.name + '</div><input type="text" name="scrape_custom_fields[' + d + '][name]" placeholder="' + translate.eg_name + '" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary btn-block" ng-click="remove_field($event)"><i class="icon ion-trash-a"></i></button></span></div></div><div class="col-sm-12"><div class="input-group"><div class="input-group-addon">' +
				translate.value + '</div><input type="text" name="scrape_custom_fields[' + d + '][value]" placeholder="' + translate.xpath_placeholder + '" class="form-control" ng-model="model[\'scrape_custom_fields[' + d + '][value]\']" ng-pattern="/^///"><span class="input-group-btn"><button type="button" class="btn btn-primary btn-block" ng-click="show_iframe_single($event)"><i class="icon ion-android-locate"></i></button></span></div><p class="help-block" ng-show="form[\'scrape_custom_fields[' + d + "][value]'].$invalid && (form['scrape_custom_fields[" +
				d + "][value]'].$dirty || submitted)\">" + translate.enter_valid + '</p></div><div class="col-sm-12"><div class="input-group"><div class="input-group-addon">' + translate.attribute + '</div><input type="text" name="scrape_custom_fields[' + d + '][attribute]" placeholder="' + translate.eg_href + '" class="form-control"></div></div><div class="col-sm-12" ng-show="model.scrape_custom_fields[' + d + '].template_status"><div class="input-group"><div class="input-group-addon">' + translate.template +
				'</div><input type="text" name="scrape_custom_fields[' + d + '][template]" placeholder="' + translate.eg_scrape_value + '" class="form-control"></div><div class="input-tags"><button type="button" class="btn btn-primary btn-xs" data-value=\'[scrape_value]\'>' + translate.btn_value + '</button><button type="button" class="btn btn-primary btn-xs" data-value=\'calc([scrape_value] + 0)\'>' + translate.btn_calculate + '</button><button type="button" class="btn btn-primary btn-xs" data-value=\'[scrape_date]\'>' +
				translate.btn_date + '</button><button type="button" class="btn btn-primary btn-xs" data-value=\'[scrape_url]\'>' + translate.btn_source_url + '</button><button type="button" class="btn btn-primary btn-xs" data-value=\'{{amazon_product_url()}}\' ng-if="special_url == \'amazon\'"><i class="fa fa-amazon"></i> ' + translate.btn_product_url + '</button><button type="button" class="btn btn-primary btn-xs" data-value=\'{{amazon_cart_url()}}\' ng-if="special_url == \'amazon\'"><i class="fa fa-amazon"></i> ' +
				translate.btn_cart_url + '</button></div></div><div class="separator"><div class="col-sm-12"><div class="form-group" ng-show="model.scrape_custom_fields[' + d + '].regex_status"><div class="col-sm-12 text-center"><button type="button" class="btn btn-link" ng-click="add_field($event, \'custom_field_regex\')"><i class="icon ion-plus-circled"></i> ' + translate.add_new_replace + '</button></div></div><div class="form-group"><div class="col-sm-12"><div class="checkbox"><label><input type="checkbox" name="scrape_custom_fields[' +
				d + '][template_status]" ng-model="model.scrape_custom_fields[' + d + '].template_status"> ' + translate.enable_template + '</label></div><div class="checkbox pro"><label><input type="checkbox"> ' + translate.enable_find_replace + "</label></div></div></div></div></div></div>");
			k($(b.target).closest(".form-group").prev())(a);
			a.set_custom_field_name_auto_complete();
		}
	};
	a.remove_field = function(a) {
		$(a.target).closest(".form-group").remove();
		$(a.target).closest(".form-group").remove();
	};
	a.update_categories = function(b) {
		var c = $("#loading");
		a.model.scrape_categoryxpath_tax = null;
		$.ajax({url:ajaxurl, type:"post", dataType:"html", data:{action:"get_post_cats_lite", post_type:b, post_id:$("#post_ID").val()}, beforeSend:function() {
			c.modal({backdrop:"static"});
		}, success:function(b) {
			var d = $('[ng-model="model.scrape_post_type"]').parents().find(".overflow");
			b ? (a.model.category_exists = !0, a.$apply(), d.replaceWith(k('<div class="overflow">' + b + "</div>")(a))) : (a.model.category_exists = !1, a.$apply());
			c.modal("hide");
		}});
		$.ajax({url:ajaxurl, type:"post", dataType:"html", data:{action:"get_post_tax_lite", post_type:b, post_id:$("#post_ID").val()}, beforeSend:function() {
			c.modal({backdrop:"static"});
		}, success:function(b) {
			var d = $('[ng-model="model.scrape_categoryxpath_tax"]');
			b ? (a.model.taxonomy_exists = !0, a.$apply(), d.replaceWith(k('<select name="scrape_categoryxpath_tax" class="form-control" ng-model="model.scrape_categoryxpath_tax"><option value="">' + translate.select_taxonomy + "</option>" + b + "</select>")(a))) : (a.model.taxonomy_exists = !1, a.$apply());
			c.modal("hide");
		}});
		a.set_custom_field_name_auto_complete();
	};
	a.set_iframe = function() {
		var b, c = $("#error"), d = $("#loading"), f = $("#iframe"), e = f.find("iframe"), h = $("#iframe_serial"), g = $("#iframe_single");
		a.show_iframe = function(e, m) {
			a.input_current = $(e.target).closest(".input-group").find('input[type="text"]');
			a.input_post_item = $('input[type="text"][name="scrape_listitem"]');
			a.input_featured_image = $('input[type="text"][name="scrape_featured"]');
			"single" == a.model.scrape_type && (a.form.scrape_url.$valid ? (b = ajaxurl + "?action=get_url_lite&address=" + encodeURIComponent(a.model.scrape_url), g.show(), g.attr("src") == b ? f.modal("show") : (g.attr("src", b), d.modal({backdrop:"static"}))) : (a.error = translate.source_url_not_valid, c.modal("show"), f.modal("hide")));
			"feed" == a.model.scrape_type && (a.form.scrape_url.$valid ? (b = ajaxurl + "?action=get_url_lite&address=" + encodeURIComponent(a.model.scrape_url) + "&scrape_feed=1", g.show(), g.attr("src") == b ? f.modal("show") : (g.attr("src", b), d.modal({backdrop:"static"}))) : (a.error = translate.source_url_not_valid, c.modal("show"), f.modal("hide")));
			f.on("hidden.bs.modal", function() {
				h.hide();
				g.hide();
			});
		};
		e.on("load", function() {
			var b = $(this);
			a.toggle_iframe_styles();
			d.modal("hide");
			f.modal("show");
			$(this).contents().find("head").append($("<link/>", {rel:"stylesheet", type:"text/css", href:plugin_path + "/wpig-simple-gallery-for-instagram/assets/css/iframe.css", id:"ol_scrapes_inspector"}));
			$(this).contents().on("mouseover", function(a) {
				$(a.target).addClass("ol_scrapes_inspector");
			}).on("mouseout", function(a) {
				$(a.target).removeClass("ol_scrapes_inspector");
			}).on("click", function(d) {
				d.preventDefault();
				if (b.attr("id") == g.attr("id")) {
					if (a.input_featured_image.length && a.form.scrape_featured.$pristine && !a.input_featured_image.val().length) {
						for (var e = ['//meta[@itemprop="image"]/@content', '//meta[@property="og:image"]/@content', '//meta[@name="twitter:image"]/@content'], h = 0;h < e.length;h++) {
							if (a.convert_xpath_to_jquery(g, e[h]).length) {
								a.featured_image_found = e[h];
								break;
							}
						}
						a.input_featured_image.val(a.featured_image_found);
						eval("a." + a.input_featured_image.attr("ng-model") + " = '" + a.input_featured_image.val() + "'");
					}
					if (a.input_current.attr("name") == a.input_featured_image.attr("name")) {
						if (e = a.check_element("img", a.convert_xpath_to_jquery(g, a.get_xpath(d.target))), 1 == e) {
							img_xpath = a.get_xpath(d.target), img_xpath = img_xpath.split(" | "), img_xpath = 2 == img_xpath.length ? img_xpath[0] + "/@src | " + img_xpath[1] + "/@src" : img_xpath[0] + "/@src", a.input_current.val(img_xpath);
						} else {
							if ("object" === typeof e) {
								img_xpath = a.get_xpath(e), img_xpath = img_xpath.split(" | "), img_xpath = 2 == img_xpath.length ? img_xpath[0] + "/@src | " + img_xpath[1] + "/@src" : img_xpath[0] + "/@src", a.input_current.val(img_xpath);
							} else {
								return a.error = translate.item_not_image, c.modal("show"), f.modal("hide"), !1;
							}
						}
					} else {
						a.input_current.val(a.get_xpath(d.target));
					}
				}
				eval("a." + a.input_current.attr("ng-model") + " = '" + a.input_current.val() + "'");
				a.$apply();
				f.modal("hide");
			});
		});
	};
	a.show_iframe_serial = function(b) {
		a.show_iframe(b, "serial");
	};
	a.show_iframe_single = function(b) {
		a.show_iframe(b, "single");
	};
	a.toggle_iframe_styles = function() {
		$("#iframe").find("iframe").each(function() {
			var b = $(this).contents(), c = $(this).contents().find("[style]");
			if (a.iframe_styles) {
				for (b.find('link[rel="stylesheet"]').not("#ol_scrapes_inspector").attr("disabled", "disabled"), b.find('style[media="print"]').remove(), b.find("style").attr("media", "print"), b = 0;b < c.length;b++) {
					$(c[b]).attr("data-style", $(c[b]).attr("style")), $(c[b]).removeAttr("style");
				}
			} else {
				for (b.find('link[rel="stylesheet"]').removeAttr("disabled"), b.find('style[media="print"]').attr("media", "screen"), c = b.find("[data-style]"), b = 0;b < c.length;b++) {
					$(c[b]).attr("style", $(c[b]).attr("data-style")), $(c[b]).removeAttr("data-style");
				}
			}
		});
	};
	a.check_element = function(a, c) {
		return c.is(a) ? !0 : c.find(a + ":first").is(a) ? c.find(a + ":first").get(0) : c.parents().find(a + ":last").is(a) ? c.parents().find(a + ":last").get(0) : !1;
	};
	a.get_absolute_xpath = function(a) {
		var b = [];
		$($(a).parents().addBack().get().reverse()).each(function() {
			var a = this.nodeName.toLowerCase(), c = a;
			0 < $(this).siblings(c).length && (a += "[" + ($(this).prevAll(a).length + 1) + "]");
			b.push(a);
		});
		return "//" + b.reverse().join("/");
	};
	a.get_next_page_xpath = function(b) {
		var c = [];
		$($(b).parents()).each(function() {
			var a = this.nodeName.toLowerCase();
			if ($(this).attr("class")) {
				return a += '[contains(concat (" ", normalize-space(@class), " "), " ' + $(this).attr("class").trim().replace(/\s+/g, " ") + ' ")]', c.push(a), !1;
			}
		});
		name_tag = "a";
		$(b).attr("class") && (name_tag += '[contains(concat (" ", normalize-space(@class), " "), " ' + $(b).attr("class").trim().replace(/\s+/g, " ") + ' ")]');
		a.input_next_page_innerhtml.val($(b).text().replace(/\s+/g, " "));
		eval("a." + a.input_next_page_innerhtml.attr("ng-model") + " = '" + a.input_next_page_innerhtml.val() + "'");
		c.push(name_tag);
		return "//" + c.join("//");
	};
	a.get_xpath = function(b) {
		var c = [], d = 0;
		$($(b).parents().addBack().get().reverse()).each(function() {
			var b = this.nodeName.toLowerCase(), e = b;
			if ("body" == e) {
				return !1;
			}
			$(this).hasClass("ol_scrapes_inspector") && $(this).removeClass("ol_scrapes_inspector");
			if ($(this).attr("id") && (non_digits = $(this).attr("id").split(/\s+/).filter(function(a) {
					return !/\d/.test(a);
				}).join(" "), "" != non_digits)) {
				return b += '[@id="' + non_digits + '"]', c.push(b), !1;
			}
			0 < $(this).siblings(e).length && (b += "[" + ($(this).prevAll(b).length + 1) + "]");
			if ($(this).attr("class") && (non_digits = $(this).attr("class").split(/\s+/).filter(function(a) {
					return !/\d/.test(a);
				}).join(" "), "" != non_digits && (non_digits = non_digits.trim().replace(/\s+/g, " "), b += '[contains(concat (" ", normalize-space(@class), " "), " ' + non_digits + ' ")]', $elements = a.convert_xpath_to_jquery($("#iframe_single"), "//" + e + '[contains(concat (" ", normalize-space(@class), " "), " ' + non_digits + ' ")]'), 1 == $elements.length && 0 == d))) {
				return c = [], c.push(e + '[contains(concat (" ", normalize-space(@class), " "), " ' + non_digits + ' ")]'), !1;
			}
			d++;
			c.push(b);
		});
		return 0 == d ? "//" + c.reverse().join("/") : "//" + c.reverse().join("/") + " | " + a.get_absolute_xpath(b);
	};
	a.convert_xpath_to_jquery = function(a, c) {
		var b, f = [];
		b = a[0].contentWindow.document;
		for (var e = b.evaluate(c, b, null, XPathResult.ORDERED_NODE_ITERATOR_TYPE, null);b = e.iterateNext();) {
			f.push(b);
		}
		return $([]).pushStack(f);
	};
	a.init = function() {
		l(function() {
			a.pro();
			a.set_iframe();
			a.set_sidebar();
			a.set_template_tags();
			a.set_custom_field_name_auto_complete();
		});
		a.$watchCollection(function() {
			return a.model.scrape_url;
		}, function() {
			l(function() {
				var b = a.model.scrape_url;
				if (/(\/|\.)amazon\./.test(b)) {
					var c = b.match(/^(?:https?:)?(?:\/\/)?([^\/\?]+)/i)[0];
					a.amazon_product_url = function() {
						return c + "/dp/[scrape_asin]?tag=AMAZON_ASSOCIATE_TAG";
					};
					a.amazon_cart_url = function() {
						return c + "/gp/aws/cart/add.html?AssociateTag=AMAZON_ASSOCIATE_TAG&SubscriptionId=AMAZON_SUBSCRIPTION_ID&ASIN.1=[scrape_asin]&Quantity.1=1";
					};
					a.special_url = "amazon";
				} else {
					a.special_url = !1;
				}
			});
		});
		a.$watchCollection(function() {
			return a;
		}, function(b, c) {
			b !== c && (a.set_popover(), a.set_radio_value());
		});
		$(window).resize(function() {
			a.set_sidebar();
		});
	};
}]);
jQuery = jQuery_scrapes.noConflict();
$ = jQuery_scrapes.noConflict();