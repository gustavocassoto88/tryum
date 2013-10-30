;
(function(defaults, $, window, undefined) {
	'use strict';
	$.expr[':'].viewable = function(element, index, data) {
		var
		w = $(window),
			e = $(element),
			tolerance = /^\d+(\.\d+)?$/.test(data[3]) ? Number(data[3]) : 0,
			windowHeight = w.height() + tolerance,
			windowWidth = w.width() + tolerance,
			windowTop = w.scrollTop() - tolerance,
			windowLeft = w.scrollLeft() - tolerance,
			elementHeight = e.height(),
			elementWidth = e.width(),
			elementTop = e.offset().top,
			elementLeft = e.offset().left;
		return ((elementLeft + elementWidth) > windowLeft ? elementLeft < (windowLeft + windowWidth) : false) && ((elementTop + elementHeight) > windowTop ? elementTop < (windowTop + windowHeight) : false);
	};
	var
	name = 'deload',
		namespace = '.' + name,
		events = 'load resize scroll orientationchange'.replace(/(\s+|$)/g, namespace + '$1'),
		animations = {
			fade: function() {
				$(this).fadeIn({
					duration: defaults.duration,
					complete: defaults.onShow,
					easing: defaults.easing
				});
			},
			show: function() {
				$(this).show({
					duration: defaults.duration,
					complete: defaults.onShow,
					easing: defaults.easing
				});
			},
			slide: function() {
				$(this).slideDown({
					duration: defaults.duration,
					complete: defaults.onShow,
					easing: defaults.easing
				});
			},
			none: function() {
				$(this).fadeIn({
					duration: 0,
					complete: defaults.onShow
				});
			}
		}, show = function() {
			$(defaults.tag.name + ':viewable(' + (defaults.tolerance || 0) + ')').filter(function() {
				return $.isPlainObject($(this).data(name));
			}).each(function() {
				var
				element = $(this),
					data = element.data(name);
				element.removeData(name).html($('<img>', {
					attr : {
						width: data.width,
						height: data.height,
						src: data.image,
						alt: data.alt,
						title: data.alt,
						'class': data.class
					},
					css: {
						display: 'none'
					},
					load: function() {
						if (animations.hasOwnProperty(data.animation)) {
							animations[data.animation].call(this);
						} else if (typeof defaults.animation === 'function') {
							defaults.animation.call(this);
						} else if (animations.hasOwnProperty(defaults.animation)) {
							animations[defaults.animation].call(this);
						} else {
							animations.fade.call(this);
						}
					},
					error: defaults.onError
				}));
			});
		}, init = function() {
			$('noscript').filter(function() {
				return $(this).data(name) !== undefined;
			}).each(function() {
				var
				element = $(this),
					data = element.data();
				element.replaceWith($('<' + defaults.tag.name + '>', (defaults.tag.options || {})).css({
					display: 'block',
					width: data.width || 'auto',
					height: data.height || 'auto',
					position: 'relative',
					float: 'left',
				}).data(name, {
					image: data.deload,
					width: data.width || 'auto',
					height: data.height || 'auto',
					alt: data.alt || '',
					'class': data.class || '',
					animation: data.animation
				}));
			});
			show();
			$(window).off(namespace).on(events, show);
		};
	$(init);
	$[name] = function(options) {
		init();
		return $.extend(defaults, options);
	};
})({
	animation: 'fade',
	easing: 'swing',
	duration: 400,
	tag: {
		name: 'span',
		options: {}
	},
	onShow: $.noop,
	onError: $.noop,
	tolerance: 0
}, jQuery, window);