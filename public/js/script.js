(function() {
    var $;
    $ = this.jQuery || window.jQuery;
    win = $(window), body = $('body'), doc = $(document);

    $.fn.hc_accordion = function() {
        var acd = $(this);
        acd.find('ul>li').each(function(index, el) {
            if ($(el).find('ul li').length > 0) $(el).prepend('<button type="button" class="acd-drop"></button>');
        });
        acd.on('click', '.acd-drop', function(e) {
            e.preventDefault();
            var ul = $(this).nextAll("ul");
            if (ul.is(":hidden") === true) {
                ul.parent('li').parent('ul').children('li').children('ul').slideUp(180);
                ul.parent('li').parent('ul').children('li').children('.acd-drop').removeClass("active");
                $(this).addClass("active");
                ul.slideDown(180);
            } else {
                $(this).removeClass("active");
                ul.slideUp(180);
            }
        });
    }

    $.fn.hc_menu = function(options) {
        var settings = $.extend({
                open: '.open-mnav',
            }, options),
            this_ = $(this);
        var m_nav = $('<div class="m-nav"><button class="m-nav-close">&times;</button><div class="nav-ct"></div></div>');
        body.append(m_nav);

        m_nav.find('.m-nav-close').click(function(e) {
            e.preventDefault();
            mnav_close();
        });

        m_nav.find('.nav-ct').append(this_.children().clone());

        var mnav_open = function() {
            m_nav.addClass('active');
            body.append('<div class="m-nav-over"></div>').css('overflow', 'hidden');
        }
        var mnav_close = function() {
            m_nav.removeClass('active');
            body.children('.m-nav-over').remove();
            body.css('overflow', '');
        }

        doc.on('click', settings.open, function(e) {
            e.preventDefault();
            if (win.width() <= 1199) mnav_open();
        }).on('click', '.m-nav-over', function(e) {
            e.preventDefault();
            mnav_close();
        });

        m_nav.hc_accordion();
    }

    $.fn.hc_countdown = function(options) {
        var settings = $.extend({
                date: new Date().getTime() + 1000 * 60 * 60 * 24,
            }, options),
            this_ = $(this);

        var countDownDate = new Date(settings.date).getTime();

        var count = setInterval(function() {
            var now = new Date().getTime();
            var distance = countDownDate - now;
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
            this_.html('<div class="item"><span>' + days + '</span> ngày</div>' +
                '<div class="item"><span>' + hours + '</span> giờ</div>' +
                '<div class="item"><span>' + minutes + '</span> phút </div>' +
                '<div class="item"><span>' + seconds + '</span> giây </div>'
            );
            if (distance < 0) {
                clearInterval(count);
            }
        }, 1000);
    }

    $.fn.hc_upload = function(options) {
        var settings = $.extend({
                multiple: false,
                result: '.hc-upload-pane',
            }, options),
            this_ = $(this);

        var input_name = this_.attr('name');
        this_.removeAttr('name');

        this_.change(function(e) {
            if ($(settings.result).length > 0) {
                var files = event.target.files;
                if (settings.multiple) {
                    for (var i = 0, files_len = files.length; i < files_len; i++) {
                        var path = URL.createObjectURL(files[i]);
                        var name = files[i].name;
                        var size = Math.round(files[i].size / 1024 / 1024 * 100) / 100;
                        var type = files[i].type.slice(files[i].type.indexOf('/') + 1);

                        var img = $('<img src="' + path + '">');
                        var input = $('<input type="hidden" name="' + input_name + '[]"' +
                            '" value="' + path +
                            '" data-name="' + name +
                            '" data-size="' + size +
                            '" data-type="' + type +
                            '" data-path="' + path +
                            '">');
                        var elm = $('<div class="hc-upload"><button type="button" class="hc-del smooth">&times;</button></div>').append(img).append(input);
                        $(settings.result).append(elm);
                    }
                } else {
                    var path = URL.createObjectURL(files[0]);
                    var img = $('<img src="' + path + '">');
                    var elm = $('<div class="hc-upload"><button type="button" class="hc-del smooth">&times;</button></div>').append(img);
                    $(settings.result).html(elm);
                }
            }
        });

        body.on('click', '.hc-upload .hc-del', function(e) {
            e.preventDefault();
            this_.val('');
            $(this).closest('.hc-upload').remove();
        });
    }

}).call(this);


jQuery(function($) {
    var win = $(window),
        body = $('body'),
        doc = $(document);

    var FU = {
        get_Ytid: function(url) {
            var rx = /^.*(?:(?:youtu\.be\/|v\/|vi\/|u\/\w\/|embed\/)|(?:(?:watch)?\?v(?:i)?=|\&v(?:i)?=))([^#\&\?]*).*/;
            if (url) var arr = url.match(rx);
            if (arr) return arr[1];
        },
        get_currency: function(str) {
            if (str) return str.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        },
        animate: function(elems) {
            var animEndEv = 'webkitAnimationEnd animationend';
            elems.each(function() {
                var $this = $(this),
                    $animationType = $this.data('animation');
                $this.addClass($animationType).one(animEndEv, function() {
                    $this.removeClass($animationType);
                });
            });
        },
    };

    var UI = {
        mMenu: function() {

        },
        header: function() {
            var elm = $('header'),
                h = elm.innerHeight(),
                offset = 200,
                mOffset = 0;
            var fixed = function() {
                elm.addClass('fixed');
                body.css('margin-top', h);
            }
            var unfixed = function() {
                elm.removeClass('fixed');
                body.css('margin-top', '');
            }
            var Mfixed = function() {
                elm.addClass('m-fixed');
                body.css('margin-top', h);
            }
            var unMfixed = function() {
                elm.removeClass('m-fixed');
                body.css('margin-top', '');
            }
            if (win.width() > 991) {
                win.scrollTop() > offset ? fixed() : unfixed();
            } else {
                win.scrollTop() > mOffset ? Mfixed() : unMfixed();
            }
            win.scroll(function(e) {
                if (win.width() > 991) {
                    win.scrollTop() > offset ? fixed() : unfixed();
                } else {
                    win.scrollTop() > mOffset ? Mfixed() : unMfixed();
                }
            });
        },
        backTop: function() {
            var back_top = $('.back-to-top'),
                offset = 800;

            back_top.click(function() {
                $("html, body").animate({ scrollTop: 0 }, 800);
                return false;
            });

            if (win.scrollTop() > offset) {
                back_top.fadeIn(200);
            }

            win.scroll(function() {
                if (win.scrollTop() > offset) back_top.fadeIn(200);
                else back_top.fadeOut(200);
            });
        },
        slider: function() {
            $('.slide-home').owlCarousel({
                loop: true,
                smartSpeed: 1000,
                /*animateOut: 'fadeOut',
                animateIn: 'fadeIn',*/
                margin: 0,
                dots: true,
                /*autoHeight: true,*/
                autoplayHoverPause: true,
                nav: false,
                /*navText: ["<span class='arrow_carrot-left'></span>", "<span>Next</span>"],
                navClass: ["slide-prev", "slide-next"],*/
                items: 1,
                autoplay: false,
                autoplayTimeout: 5000,
                onChanged: slider_change,
            });

            function slider_change(e) {
                var aniElm = $('.slide-home .owl-item').eq(e.item['index']).find("[data-animation ^= 'animated']");
                FU.animate(aniElm);
            };
            $('.sl-prd-home').owlCarousel({
                loop: true,
                smartSpeed: 1000,
                animateOut: 'fadeOut',
                animateIn: 'fadeIn',
                margin: 0,
                dots: true,
                /*autoHeight: true,*/
                autoplayHoverPause: true,
                nav: false,
                navText: ["<i class='arrow_triangle-left'></i>", "<i class='arrow_triangle-right'></i>"],
                navClass: ["slide-prev", "slide-next"],
                items: 1,
                autoplay: false,
                autoplayTimeout: 5000,
                responsiveClass: true,
                responsive: {
                    991: {
                        nav: true,
                    },

                    0: {
                        nav: false,
                    }
                },
            });

            var len = Math.ceil($('.sl-prd-sale').children().length / 4),
                index = 1;
            if ($(window).width() < 1200) {
                var len = Math.ceil($('.sl-prd-sale').children().length / 3)
            }
            if ($(window).width() < 992) {
                var len = Math.ceil($('.sl-prd-sale').children().length / 2)
            }
            if ($(window).width() < 480) {
                var len = Math.ceil($('.sl-prd-sale').children().length)
            }
            //console.log(len);
            $('.sl-prd-sale').owlCarousel({
                loop: false,
                responsiveClass: true,
                nav: true,
                dots: true,
                smartSpeed: 500,
                margin: 30,
                autoplay: true,
                slideBy: 4,
                autoplayTimeout: 5000,
                navClass: ["slide-prev", "slide-next"],
                navText: ["<i class='arrow_triangle-left'></i>", "<i class='arrow_triangle-right'></i>"],
                responsive: {
                    1199: {
                        items: 4,
                        slideBy: 4,
                    },
                    991: {
                        items: 3,
                        slideBy: 3,
                    },
                    479: {
                        items: 2,
                        slideBy: 2,
                    },
                    0: {
                        items: 1,
                        slideBy: 1,
                    }
                },
                onTranslate: slide_callback,
            });

            function add0(num) {
                var num = Number(num);
                if (num < 10) num = num;
                return num;
            };
            $('.sl-prd-sale').after('<div class="slider-nums"><strong>' + add0(index) + '</strong> / <span>' + add0(len) + '</span></div>');

            function slide_callback(e) {
                setTimeout(function() {
                    $('.slider-nums').find('strong').text(add0(e.page.index + 1));
                }, 100)
            }

            $('.sl-blog-home').owlCarousel({
                loop: false,
                responsiveClass: true,
                nav: true,
                dots: false,
                smartSpeed: 500,
                margin: 10,
                autoplay: true,
                autoplayTimeout: 5000,
                navClass: ["slide-prev", "slide-next"],
                navText: ["<i class='arrow_triangle-left'></i>", "<i class='arrow_triangle-right'></i>"],
                responsive: {
                    1199: {
                        items: 2,
                    },
                    991: {
                        items: 2,
                    },

                    479: {
                        items: 1,
                    },
                    0: {
                        items: 1,
                    }
                }
            });
            $('.sl-branch-home').owlCarousel({
                loop: false,
                responsiveClass: true,
                nav: true,
                dots: false,
                smartSpeed: 500,
                margin: 30,
                autoplay: true,
                autoplayTimeout: 5000,
                navClass: ["slide-prev", "slide-next"],
                navText: ["<i class='arrow_triangle-left'></i>", "<i class='arrow_triangle-right'></i>"],
                responsive: {
                    1199: {
                        items: 5,
                    },
                    991: {
                        items: 3,
                    },

                    479: {
                        items: 2,
                    },
                    0: {
                        items: 2,
                    }
                }
            });
            $('.sl-proDt').owlCarousel({
                loop: true,
                smartSpeed: 1000,
                /*animateOut: 'fadeOut',
                animateIn: 'fadeIn',*/
                margin: 0,
                dots: true,
                /*autoHeight: true,*/
                autoplayHoverPause: true,
                nav: true,
                navClass: ["slide-prev", "slide-next"],
                navText: ["<i class='arrow_triangle-left'></i>", "<i class='arrow_triangle-right'></i>"],
                items: 1,
                autoplay: false,
                autoplayTimeout: 5000,
            });
            /*$('.slider-cas').slick({
                nextArrow: '<img src="images/next.png" class="next" alt="Next">',
                prevArrow: '<img src="images/prev.png" class="prev" alt="Prev">',
            })
            FU.animate($(".slider-cas .slick-current [data-animation ^= 'animated']"));
            $('.slider-cas').on('beforeChange', function(event, slick, currentSlide, nextSlide){
                if(currentSlide!=nextSlide){
                    var aniElm = $(this).find('.slick-slide').find("[data-animation ^= 'animated']");
                    FU.animate(aniElm);
                }
            });*/

            /*$('.pro-cas').slick({
                slidesToShow: 4,
                slidesToScroll: 1,
                nextArrow: '<i class="cas-arrow smooth next"></i>',
                prevArrow: '<i class="cas-arrow smooth prev"></i>',
                dots: true,
                autoplay: true,
                swipeToSlide: true,
                autoplaySpeed: 4000,
                responsive: [
                {
                    breakpoint: 1199,
                    settings: {
                        slidesToShow: 3,
                    }
                },
                {
                    breakpoint: 991,
                    settings: {
                        slidesToShow: 3,
                    }
                },
                {
                    breakpoint: 700,
                    settings: {
                        slidesToShow: 2,
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                    }
                }
                ],
            })*/

            /*$('.pro-cas').owlCarousel({
                loop: true,
                margin: 30,
                responsiveClass:true,
                nav: true,
                smartSpeed: 800,
                navText: ["<span class='smooth arrow-cas prev'></span>", "<span class='smooth arrow-cas next'></span>"],
                responsive:{
                    992:{
                        items: 3,
                    },
                    768:{
                        items: 2,
                    },
                    0:{
                        items: 1,
                    }
                }
            })*/
        },
        input_number: function() {
            doc.on('keydown', '.numberic', function(event) {
                if (!(!event.shiftKey &&
                        !(event.keyCode < 48 || event.keyCode > 57) ||
                        !(event.keyCode < 96 || event.keyCode > 105) ||
                        event.keyCode == 46 ||
                        event.keyCode == 8 ||
                        event.keyCode == 190 ||
                        event.keyCode == 9 ||
                        event.keyCode == 116 ||
                        (event.keyCode >= 35 && event.keyCode <= 39)
                    )) {
                    event.preventDefault();
                }
            });
            doc.on('click', '.i-number .up', function(e) {
                e.preventDefault();
                var input = $(this).parents('.i-number').children('input');
                var max = Number(input.attr('max')),
                    val = Number(input.val());
                if (!isNaN(val)) {
                    if (!isNaN(max) && input.attr('max').trim() != '') {
                        if (val >= max) {
                            return false;
                        }
                    }
                    input.val(val + 1);
                    input.trigger('number.up');
                }
            });
            doc.on('click', '.i-number .down', function(e) {
                e.preventDefault();
                var input = $(this).parents('.i-number').children('input');
                var min = Number(input.attr('min')),
                    val = Number(input.val());
                if (!isNaN(val)) {
                    if (!isNaN(min) && input.attr('max').trim() != '') {
                        if (val <= min) {
                            return false;
                        }
                    }
                    input.val(val - 1);
                    input.trigger('number.down');
                }
            });
        },
        yt_play: function() {
            doc.on('click', '.yt-box .play', function(e) {
                var id = FU.get_Ytid($(this).closest('.yt-box').attr('data-url'));
                $(this).closest('.yt-box iframe').remove();
                $(this).closest('.yt-box').append('<iframe src="https://www.youtube.com/embed/' + id + '?rel=0&amp;autoplay=1&amp;showinfo=0" frameborder="0" allowfullscreen></iframe>');
            });
        },
        psy: function() {
            var btn = '.psy-btn',
                sec = $('.psy-section'),
                pane = '.psy-pane';
            doc.on('click', btn, function(e) {
                e.preventDefault();
                $(this).closest(pane).find(btn).removeClass('active');
                $(this).addClass('active');
                $("html, body").animate({ scrollTop: $($(this).attr('href')).offset().top - 40 }, 600);
            });

            var section_act = function() {
                sec.each(function(index, el) {
                    if (win.scrollTop() + (win.height() / 2) >= $(el).offset().top) {
                        var id = $(el).attr('id');
                        $(pane).find(btn).removeClass('active');
                        $(pane).find(btn + '[href="#' + id + '"]').addClass('active');
                    }
                });
            }
            section_act();
            win.scroll(function() {
                section_act();
            });
        },
        toggle: function() {
            var ani = 100;
            $('[data-show]').each(function(index, el) {
                var ct = $($(el).attr('data-show'));
                $(el).click(function(e) {
                    e.preventDefault();
                    ct.fadeToggle(ani);
                });
            });
            win.click(function(e) {
                $('[data-show]').each(function(index, el) {
                    var ct = $($(el).attr('data-show'));
                    if (ct.has(e.target).length == 0 && !ct.is(e.target) && $(el).has(e.target).length == 0 && !$(el).is(e.target)) {
                        ct.fadeOut(ani);
                    }
                });
            });
        },
        uiCounterup: function() {
            var item = $('.hc-couter'),
                flag = true;
            if (item.length > 0) {
                run(item);
                win.scroll(function() {
                    if (flag == true) {
                        run(item);
                    }
                });

                function run(item) {
                    if (win.scrollTop() + 70 < item.offset().top && item.offset().top + item.innerHeight() < win.scrollTop() + win.height()) {
                        count(item);
                        flag = false;
                    }
                }

                function count(item) {
                    item.each(function() {
                        var this_ = $(this);
                        var num = Number(this_.text().replace(".", ""));
                        var incre = num / 80;

                        function start(counter) {
                            if (counter <= num) {
                                setTimeout(function() {
                                    this_.text(FU.get_currency(Math.ceil(counter)));
                                    counter = counter + incre;
                                    start(counter);
                                }, 20);
                            } else {
                                this_.text(FU.get_currency(num));
                            }
                        }
                        start(0);
                    });
                }
            }
        },
        sticky: function () {
            if ($(window).width() > 991) {
                if ($('.sb-product,.sb-checkout').length > 0) {
                    $('.sb-product,.sb-checkout').stick_in_parent({
                        offset_top: 100
                    });
                }
            }
        },
        ready: function() {
            UI.mMenu();
            //UI.header();
            UI.slider();
            UI.backTop();
            // UI.toggle();
            UI.input_number();
            //UI.uiCounterup();
            UI.sticky();
            // UI.yt_play();
            // UI.psy();
        },
    }


    UI.ready();


    /*custom here*/

    WOW.prototype.addBox = function(element) {
        this.boxes.push(element);
    };
    var wow = new WOW({
        mobile: false
    });
    wow.init();
    if ($(window).width() > 991) {
        $('.wow').on('scrollSpy:exit', function() {
            $(this).css({
                'visibility': 'hidden',
                'animation-name': 'none'
            }).removeClass('animated');
            wow.addBox(this);
        }).scrollSpy();
    }

    $('.mb-nav').hc_menu({
        open: '.icon-menu',
    });
    //menu header scroll
    $(window).scroll(function() {
        if ($(window).scrollTop() > 0) $('header').addClass('scroll');
        else $('header').removeClass('scroll toggle-menu');
    });
    if ($(window).width() > 1199) {
        $('.icon-menu').click(function() {
            $('header').removeClass('scroll').addClass('toggle-menu');
        });
    }
    $('.icon-search-mb').click(function(event) {
        $(this).children('i').toggleClass('icon_search icon_close');
        $('header .hd-center').toggleClass('show');
    });

    $('.ft-item .head').click(function(event) {
        $(this).children('span').toggleClass('icon_plus icon_minus-06');
        $(this).next('.ft-mb').slideToggle();
    });
    $('.sb-title').click(function(event) {
        $(this).children('i').toggleClass('arrow_triangle-up arrow_triangle-down');
        $(this).next('.sb-ct').slideToggle(300);
        $(this).next().next('.readmore').slideToggle(300);
    });

    $("#slider-range").each(function() {
        var get_currency = function(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
        var this_ = $(this);

        var min = parseInt(this_.attr('data-min'));

        var max = parseInt(this_.attr('data-max'));
        var valu = this_.attr('data-value').split(",");
        var rang = max - min;

        this_.slider({
            range: true,
            min: min,
            max: max,
            values: valu,
            slide: function(event, ui) {
                var left1 = (ui.values[0] - min) / rang * 100;
                var price1 = this_.prevAll('.price-1').text(get_currency(ui.values[0]));
                var left2 = 100 - (ui.values[1] - min) / rang * 100;
                var price2 = this_.prevAll('.price-2').text(get_currency(ui.values[1]));
                price1.trigger('change');
                price2.trigger('change');

            }
        });

        var left1 = (this_.slider("values", 0) - min) / rang * 100;
        this_.prevAll('.price-1').text(get_currency(this_.slider("values", 0)));
        var left2 = 100 - (this_.slider("values", 1) - min) / rang * 100;
        this_.prevAll('.price-2').text(get_currency(this_.slider("values", 1)));
    });

    $('.sb-item .readmore').on('click', function () {
        var txt = $(".over.show").is(':visible') ? '<i class="arrow_carrot-down"></i>' + "more" : '<i class="arrow_carrot-up"></i>' + "less";
        $(this).html(txt);
        $(this).prev('.over').toggleClass('show');
        /*$(".over.show").is(':visible') ? $(this).attr('href','javascript:;') : $(this).attr('href','#prj-tq') ;*/
    });
    $('.ctrl-head .sort .label').click(function(event) {
        $(this).children('i').toggleClass('arrow_triangle-up arrow_triangle-down');
        $(this).next('ul').toggleClass('show');
    });
    $('.filter-btn').click(function(e) {
        e.preventDefault();
        if($('.sb-product').hasClass('active')){
            $('.sb-product').removeClass('active');
            $('.filter-over').remove();
        }
        else{
            $('.sb-product').addClass('active');
            $('body').append('<div class="filter-over"></div>');
        }
    });
    $('body').on('click', '.filter-over', function(e) {
        e.preventDefault();
        $('.sb-product').removeClass('active');
        $('.filter-over').remove();
    });
    $('.sb-ct ul li a').click(function(event) {
        $(this).parent('li').toggleClass('active');
    });
    if($(window).width()<992){
        $('.sb-account .top-sb').click(function(event) {
            $('.sb-account ul').slideToggle(300);
            $(this).children().children('i').toggleClass('arrow_triangle-up arrow_triangle-down');
        });
    }
    $('.dropdown-menu li').on('click', function() {
        var getValue = $(this).html();
        $('.dropdown-select').html(getValue);
        $('.dropdown-menu li').removeClass('active');
        $(this).addClass('active');
    });
    $('.btn-remove').click(function(event) {
        $(this).parent('.item-cart').remove();
    });
    $('.item-wishlist .btn-remove').click(function(event) {
        $(this).parent().parent().remove();
    });

    if($('.datepicker').length>0){
        $('.datepicker').datepicker();
    } 
});