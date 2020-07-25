/** PIXWELL_CORE_SCRIPT */
var PIXWELL_CORE_SCRIPT = (function (Module, $) {
    "use strict";

    /** init */
    Module.init = function () {
        var self = this;
        $(window).trigger('RB:CountBookmark');

        self.initLazyLoad();
        self.newsLetterSubmit();
        self.rbGallery();
        self.rbCookie();
        self.headerStrip();
        self.bookMarkCounter();
        self.bookmarkList();
        self.removeBookmarkList();
        self.removeBookMark();
        self.reloadSizes();
        self.newsLetterPopup();
    };

    Module.rbBookMarks = function () {
        this.loadBookMarks();
        this.addBookMark();
    };

    /** module newsletter */
    Module.newsLetterSubmit = function () {
        $('.rb-newsletter-form').submit(function (e) {
            e.preventDefault();
            e.stopPropagation();
            var target = $(this);
            var responseWrap = target.closest('.rb-newsletter').find('.newsletter-response');
            responseWrap.find('.is-show').removeClass('is-show');
            var subscribeEmail = target.find('input[name="rb_email_subscribe"]').val();
            if (!subscribeEmail) {
                responseWrap.find('.email-error').addClass('is-show');
                responseWrap.find('.email-error').addClass('showing');
                return false;
            }

            var postData = {
                action: 'rb_submit_newsletter',
                email: subscribeEmail
            };

            var privacy = target.find(':checkbox[name="rb_privacy"]');
            if (privacy != null && privacy.length > 0) {
                var privacyVal = privacy.prop('checked');
                if (!privacyVal) {
                    responseWrap.find('.privacy-error').addClass('is-show showing');
                    return false;
                } else {
                    postData.privacy = privacyVal;
                }
            }

            $.ajax({
                type: 'POST',
                url: pixwellParams.ajaxurl,
                data: postData,
                success: function (response) {
                    responseWrap.find('.' + response.notice).addClass('is-show');
                    responseWrap.find('.' + response.notice).addClass('showing');
                }
            });

            return false;
        });
    };

    /** Newsletter popup */
    Module.newsLetterPopup = function () {

        if ($(window).width() < 768) {
            return;
        }

        var targetID = '#rb-newsletter-popup';
        if ($(targetID).length > 0 && '1' !== $.cookie('ruby_newsletter_popup')) {
            var delay = $(targetID).data('delay');
            if (!delay) {
                delay = 2000;
            }
            setTimeout(function () {
                $.magnificPopup.open({
                    type: 'inline',
                    preloader: false,
                    closeBtnInside: true,
                    removalDelay: 500,
                    showCloseBtn: true,
                    closeOnBgClick: false,
                    disableOn: 992,
                    items: {
                        src: targetID,
                        type: 'inline'
                    },
                    mainClass: 'rb-popup-effect',
                    fixedBgPos: true,
                    fixedContentPos: true,
                    closeMarkup: '<button id="rb-close-newsletter" title="%title%" class="mfp-close"><i class="rbi rbi-move"></i></button>',
                    callbacks: {
                        close: function () {
                            var expiresTime = $(targetID).data('expired');
                            $.cookie('ruby_newsletter_popup', '1', {expires: parseInt(expiresTime), path: '/'});
                        }
                    }
                });

            }, delay);
        }
    };

    /** Ruby gallery */
    Module.rbGallery = function () {
        var gallery = $('.rb-gallery-wrap');
        if (gallery.length > 0) {
            gallery.each(function () {
                var el = $(this);
                var inner = el.find('.gallery-inner').eq(0);
                $(inner).isotope({
                    itemSelector: '.rb-gallery-el',
                    percentPosition: true,
                    masonry: {
                        columnWidth: inner.find('.rb-gallery-el')[0]
                    }
                });

                $(window).on('RB:LazyLoaded', function () {
                    inner.imagesLoaded().progress(function () {
                        $(inner).isotope('layout');
                    });
                });

                $('body').imagesLoaded(function () {
                    $(inner).isotope('layout');
                });

                setTimeout(function () {
                    inner.removeClass('gallery-loading');
                }, 2000);
                inner.imagesLoaded(function () {
                    inner.removeClass('gallery-loading');
                });
            });
        }
    };

    //share action
    Module.sharesAction = function () {
        $('a.share-action').off('click').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            window.open($(this).attr('href'), '_blank', 'width=600, height=350');

            return false;
        })
    };


    /** rb cookie */
    Module.rbCookie = function () {
        var rbCookie = $('#rb-cookie');
        if (rbCookie.length > 0) {
            if ($.cookie('ruby_cookie_popup') !== '1') {
                rbCookie.css('display', 'block');
                setTimeout(function () {
                    rbCookie.addClass('is-show');
                }, 10)
            }

            $('#cookie-accept').off('click').on('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                $.cookie('ruby_cookie_popup', '1', {expires: 30, path: '/'});
                rbCookie.removeClass('is-show');
                setTimeout(function () {
                    rbCookie.css('display', 'none');
                }, 500)
            })
        }
    };


    /** header strip */
    Module.headerStrip = function () {
        var headerStrips = $('.rb-headerstrip');
        if (headerStrips.length > 0) {
            headerStrips.each(function () {
                var headerStrip = $(this);
                var id = headerStrip.attr('id');
                if ($.cookie(id) !== '1') {
                    headerStrip.css('display', 'block');
                }
            });
        }

        $('.headerstrip-submit').off('click').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var wrap = $(this).parents('.rb-headerstrip');
            var expired = wrap.data('headerstrip');
            if (!expired) {
                expired = 30;
            }
            var id = wrap.attr('id');
            $.cookie(id, '1', {expires: expired, path: '/'});
            wrap.slideUp(300, function () {
                wrap.remove();
            });
        });
    };

    /** add bookmark */
    Module.addBookMark = function () {
        var self = this;
        $('.read-it-later').off('click').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            var target = $(this);
            var postID = target.data('bookmarkid');
            if (!postID) {
                return;
            }
            var dataBookMark = $.cookie('RBBookmarkData');
            if (dataBookMark) {
                dataBookMark = JSON.parse(dataBookMark);
            }
            if (typeof dataBookMark != 'object') {
                dataBookMark = [];
            }
            dataBookMark = self.toggleArrayItem(target, dataBookMark, postID);
            $.cookie('RBBookmarkData', JSON.stringify(dataBookMark), {expires: 30, path: '/'});
            $(window).trigger('RB:CountBookmark');
        });
    };

    /** toggle data */
    Module.toggleArrayItem = function (target, data, value) {
        var i = $.inArray(value, data);
        if (i === -1) {
            $('[data-bookmarkid= ' + value + ']').addClass('added');
            data.push(value);
        } else {
            $('[data-bookmarkid= ' + value + ']').removeClass('added');
            data.splice(i, 1);
        }
        return data;
    };


    /** remove bookmarks */
    Module.removeBookmarkList = function () {
        $('#remove-bookmark-list').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            $.removeCookie('RBBookmarkData', {path: '/'});
            window.location.reload();
        })
    };

    Module.loadBookMarks = function () {
        var dataBookMark = $.cookie('RBBookmarkData');
        if (dataBookMark) {
            dataBookMark = JSON.parse(dataBookMark);
        }
        $('.read-it-later:not(.loaded)').each(function () {
            var target = $(this);
            target.addClass('loaded');

            var postID = target.data('bookmarkid');
            if (!postID) {
                return;
            }
            var i = $.inArray(postID, dataBookMark);
            if (i != -1) {
                target.addClass('added');
            }
        });
        $(window).trigger('RB:CountBookmark');
    };

    /** remove single bookmark */
    Module.removeBookMark = function () {
        var removeID = $('.rb-remove-bookmark').data('bookmarkid');
        if (removeID) {
            var dataBookMark = $.cookie('RBBookmarkData');
            if (dataBookMark) {
                dataBookMark = JSON.parse(dataBookMark);
            }
            if (typeof dataBookMark != 'object') {
                return;
            }
            var i = $.inArray(removeID, dataBookMark);
            if (i != -1) {
                dataBookMark.splice(i, 1);
            }
            $.cookie('RBBookmarkData', JSON.stringify(dataBookMark), {expires: 30, path: '/'});
        }
    };

    /** total bookmarks */
    Module.bookMarkCounter = function () {
        $(window).on('RB:CountBookmark', function () {
            var dataBookMark = $.cookie('RBBookmarkData');
            if (dataBookMark) {
                dataBookMark = JSON.parse(dataBookMark);
                if (dataBookMark != null && dataBookMark.length > 0) {
                    $('.bookmark-counter').fadeOut(0).html(dataBookMark.length).fadeIn(200);
                }
            }
        });
    };

    /** ajax get bookmark list */
    Module.bookmarkList = function () {

        var bookmarkList = $('#bookmarks-list');

        if (null == bookmarkList || bookmarkList.length < 1) {
            return;
        }
        var dataBookMark = $.cookie('RBBookmarkData');
        if (dataBookMark) {
            dataBookMark = JSON.parse(dataBookMark);
        }

        $.ajax({
            type: 'POST',
            url: pixwellParams.ajaxurl,
            data: {
                action: 'rb_bookmark',
                ids: dataBookMark
            },
            success: function (data) {
                data = $.parseJSON(JSON.stringify(data));
                $('#bookmarks-list').html(data);
                $(window).trigger('load');
            }
        });
    };


    /** lazyload */
    Module.getWidth = function (imgObject) {
        var width = imgObject.parent().width() - 20;
        if (!width) {
            width = imgObject.data('sizes');
        } else {
            if (width < 1) {
                width = 1;
            }
            width += 'px';
        }
        return width;
    };

    Module.initLazyLoad = function () {
        var self = this;
        $(window).on('load RB:LazyLoad', function () {
            var imageData = $('.rb-lazyload');
            if (imageData.length > 0) {
                imageData.each(function () {
                    var item = $(this);
                    if (item.hasClass('rb-autosize')) {
                        item.attr('sizes', self.getWidth(item));
                    } else {
                        item.attr('sizes', item.data('sizes'));
                    }
                });
                imageData.lazyload();
                $(window).trigger('RB:LazyLoaded');
            }
        });
    };

    Module.reloadSizes = function () {
        var self = this;
        $(window).resize(function () {
            $('.rb-lazyload').each(function () {
                var imgObject = $(this);
                imgObject.attr('sizes', self.getWidth(imgObject));
            });
        })
    };

    return Module;

}(PIXWELL_CORE_SCRIPT || {}, jQuery));


jQuery(document).ready(function () {
    PIXWELL_CORE_SCRIPT.init();
});

jQuery(window).on('load', function () {
    PIXWELL_CORE_SCRIPT.sharesAction();
    PIXWELL_CORE_SCRIPT.rbBookMarks();
});
