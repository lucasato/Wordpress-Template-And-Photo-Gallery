/*
 * EasyJqueryGallery - jQuery Plugin
 * Simple Gallery
 *
 * Examples and documentation at: http://tiendas-digitales.net
 *
 * Copyright (c) 2017 Lucas Gabriel Martinez
 *
 * Version: 1.0.0 - 2017/07/10
 * Requires: jQuery v1.3+
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */
$(document).ready(function(e) {
    $.fn.easyGalleryJquery = function(options) {
        var params = $.fn.extend({
            percent: 80,
            title: 'Gallery',
        }, options);
        var div = $(this);
        var loader;
        var fadedBackground = function() {
            if ($('.easyGalleryJqueryBack').length < 1) {
                $('body').append('<div class="easyGalleryJqueryBack"></div>')
            }
            $('.easyGalleryJqueryBack').css({
                'width': $(window).width(),
                'height': $(document).height() + ((screen.height * 15) / 100),
                'display': 'block'
            })
        }
        var close = function() {
            $('.easyGalleryJqueryTop input,.easyGalleryJqueryBack').click(function(e) {
                $("body").css("overflow", "auto");
                $('.easyGalleryJquery').css({
                    'display': 'none'
                });
                $('.easyGalleryJqueryBack').fadeTo(500, 0, '', function() {
                    $(this).remove()
                });
                $(window).unbind('resize')
            })
        }
        var img_list = Array();
        var img_i = 0;
        var img_x = 0;
        var img_y = 0;
        var main;
        var img;
        var left_button, right_button, close_button;
        var showing_id = 0;
        var newWindow = function() {
            $('body').append('<div class="easyGalleryJquery">\
 							<div class="easyGalleryJqueryTop"><h1>' + params.title + '</h1><input type="button" value="x"/></div>\
 							<div class="easyGallerymoveLeft"></div>\
 							<ul>\
 								<li class="easyGalleryContent">\
 									<figure>\
 										<img src="" alt="caption"/>\
 									</figure>\
 								</li>\
 							</ul>\
 							<div class="easyGallerymoveRight"></div>\
 							<div class="easyGalleryJqueryBottom">test<div>\
 						</div>');
            $('body').append('<div class="easyGalleryLoader"><div class="easyGalleryLoaderGif"></div></div>');
            main = $('.easyGalleryJquery');
            loader = $('.easyGalleryLoader');
            img = $('.easyGalleryContent').find('img');
            left_button = $('.easyGallerymoveLeft');
            right_button = $('.easyGallerymoveRight');
            close_button = $('.easyGalleryJqueryTop input');
            screenParams();
            left_button.click(function(e) {
                e.preventDefault();
                left_button.css({
                    'display': 'none'
                });
                right_button.css({
                    'display': 'none'
                });
                close_button.css({
                    'display': 'none'
                });
                showing_id--;
                if (showing_id >= 0) {
                    img_list[showing_id].parent().parent().find('a').click()
                } else {
                    showing_id = img_list.length - 1;
                    img_list[showing_id].parent().parent().find('a').click()
                }
            });
            right_button.click(function(e) {
                e.preventDefault();
                left_button.css({
                    'display': 'none'
                });
                right_button.css({
                    'display': 'none'
                });
                close_button.css({
                    'display': 'none'
                });
                if (showing_id >= img_list.length - 1) {
                    var next = 0;
                    img_list[next].parent().parent().find('a').click();
                    showing_id = next
                } else {
                    showing_id++;
                    var next = showing_id;
                    img_list[next].parent().parent().find('a').click();
                    showing_id = next
                }
            });
            left_button.mouseover(function(e) {
                e.preventDefault();
                $(this).css({
                    'opacity': 0.9
                })
            });
            left_button.mouseout(function(e) {
                e.preventDefault();
                $(this).css({
                    'opacity': 0.6
                })
            });
            right_button.mouseover(function(e) {
                e.preventDefault();
                $(this).css({
                    'opacity': 0.9
                })
            });
            right_button.mouseout(function(e) {
                e.preventDefault();
                $(this).css({
                    'opacity': 0.6
                })
            });
            content_top = $(window).scrollTop() + (($(window).height() - main.height()));
            div.find('img').each(function() {
                img_list[img_i] = $(this);
                var caption = $(this).attr('alt');
                var url = (img_list[img_i].parent().parent().find('a').attr('href'));
                var id_ = img_i;
                img_list[img_i].parent().parent().find('a').attr('href', 'Javascript:void(0);');
                $(this).parent().parent().find('a').click(function(e) {
                    fadedBackground();
                    showing_id = id_;
                    loadImage(url, caption);
                    startLoader();
                    $('.easyGalleryJqueryTop,.easyGalleryJqueryBottom').css({
                        'display': 'none'
                    })
                });
                img_i++
            });
            img_i = 0;
            main.css({
                'display': 'none'
            })
        };
        var loadImage = function(url, caption) {
            img.attr('src', '');
            img.attr('src', url);
            $('.easyGalleryJqueryBottom').html(caption);
            img.css({
                'opacity': 0
            });
            left_button.css({
                'display': 'none'
            });
            right_button.css({
                'display': 'none'
            });
            img.unbind();
            img.on('load', function(e) {
                e.preventDefault();
                var tmp = new Image()
                tmp.src = url;
                img_x = tmp.width;
                img_y = tmp.height;
                showImage();
                closeLoader()
            });
            img.on('error', function(e) {
                alert('La imagen no pudo ser cargada. Error: #1');
                $("body").css("overflow", "auto");
                $('.easyGalleryJquery').css({
                    'display': 'none'
                });
                $('.easyGalleryJqueryBack').fadeTo(500, 0, '', function() {
                    $(this).remove()
                });
                $(window).unbind('resize');
                closeLoader()
            })
        };
        var reduce_bars, screenY, screenX, content_left;
        var screenParams = function() {
            reduce_bars = $('.easyGalleryJqueryTop').height() + $('.easyGalleryJqueryBottom').height();
            screenY = (($(window).height() * params.percent) / 100) - reduce_bars;
            screenX = ($(window).width() * params.percent) / 100;
            content_left = (($(window).width() * ((100 - params.percent) / 100) / 2));
            main.css({
                'position': 'absolute',
                'left': content_left,
                'width': screenX,
                'height': screenY
            })
        }
        var update_vars = function() {
            screenY = (($(window).height() * params.percent) / 100);
            screenX = ($(window).width() * params.percent) / 100
        }
        var startLoader = function() {
            var _top = $(window).scrollTop() + ($(window).height() / 2) - (loader.height() / 2);
            loader.css({
                'top': _top,
                'left': $(window).width() / 2 - (loader.width() / 2),
                opacity: 0,
                'display': 'block'
            }).animate({
                opacity: 1
            }, 500);
        }
        var closeLoader = function() {
            loader.animate({
                opacity: 0
            }, 500, '', function() {
                loader.css({
                    'display': 'none'
                })
            })
        }
        var showImage = function() {
            main.css({
                'display': 'block'
            });
            $("body").css("overflow", "hidden");
            update_vars();
            main.center();
            var buttons_top = $('.easyGalleryJqueryTop').height();
            if (img_x > screenX && img_y <= screenY) {
                var scale = screenX / img_x;
                img_x = screenX;
                img_y = img_y * scale
            } else if (img_y > screenY && img_x < screenX) {
                var scale = screenY / img_y;
                img_y = screenY;
                img_x = img_x * scale
            } else if (img_y > screenY && img_x > screenX) {
                var coefic = 1.5;
                var m = (img_y / img_x).toFixed(4);
                img_y = m * img_y;
                img_x = m * img_x;
                while (img_y > screenY || img_x > screenX) {
                    img_x = img_x / coefic;
                    img_y = img_y / coefic
                }
            }
            var adapt_left = ($(window).width() / 2) - (img_x / 2);
            var adapt_top = $(window).scrollTop() + ($(window).height() / 2) - (img_y / 2) - (reduce_bars / 2);
            main.animate({
                width: img_x,
                height: img_y + reduce_bars,
                left: adapt_left,
                top: adapt_top
            }, 500, 'linear', function() {
                left_button.css({
                    'height': img_y,
                    'position': 'absolute',
                    'left': 0,
                    'top': buttons_top,
                    'display': 'block',
                    'opacity': 0.6
                });
                close_button.css({
                    'display': 'block',
                    'left': img_x - close_button.width()
                });
                right_button.css({
                    'height': img_y,
                    'position': 'absolute',
                    'left': main.width() - ($('.easyGallerymoveLeft').width()),
                    'top': buttons_top,
                    'display': 'block',
                    'opacity': 0.6
                });
                $('.easyGalleryJqueryTop,.easyGalleryJqueryBottom').css({
                    'display': 'block'
                });
                img.css({
                    'width': img_x,
                    'height': img_y,
                    'display': 'block'
                });
                img.animate({
                    opacity: 1
                }, 500)
            });
            $(window).resize(function(e) {
                update_vars();
                main.center();
                fadedBackground();
                var adapt_left = ($(window).width() / 2) - (img_x / 2);
                main.css({
                    'width': img_x,
                    'height': img_y + reduce_bars,
                    'left': adapt_left
                });
                img.css({
                    'width': img_x,
                    'height': img_y,
                    'display': 'block'
                })
            });
            close()
        }
        newWindow()
    };
    jQuery.fn.center = function() {
        this.css("top", (($(window).height() - this.outerHeight()) / 2) + $(window).scrollTop() + "px");
        this.css("left", (($(window).width() - this.outerWidth()) / 2) + $(window).scrollLeft() + "px");
        return this
    }
})