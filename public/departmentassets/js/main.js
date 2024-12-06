(function ($) {
    "use strict";
    /*--
        Commons Variables
    -----------------------------------*/
    var $window = $(window);
    var $body = $('body');

    /*--
        dash Dropdown (Custom Dropdown)
    -----------------------------------*/
    if ($('.m-dropdown').length) {
        var $dashDropdown = $('.m-dropdown'),
            $dashDropdownMenu = $dashDropdown.find('.m-dropdown-menu');

        $dashDropdown.on('click', '.toggle', function (e) {
            e.preventDefault();
            var $this = $(this);
            if (!$this.parent().hasClass('show')) {
                $dashDropdown.removeClass('show');
                $dashDropdownMenu.removeClass('show');
                $this.siblings('.m-dropdown-menu').addClass('show').parent().addClass('show');
            } else {
                $this.siblings('.m-dropdown-menu').removeClass('show').parent().removeClass('show');
            }
        });
        /*Close When Click Outside*/
        $body.on('click', function (e) {
            var $target = e.target;
            if (!$($target).is('.m-dropdown') && !$($target).parents().is('.m-dropdown') && $dashDropdown.hasClass('show')) {
                $dashDropdown.removeClass('show');
                $dashDropdownMenu.removeClass('show');
            }
        });
    }

    /*--
        Header Search Open/Close
    -----------------------------------*/
    var $headerSearchOpen = $('.header-search-open'),
        $headerSearchClose = $('.header-search-close'),
        $headerSearchForm = $('.header-search-form');
    $headerSearchOpen.on('click', function () {
        $headerSearchForm.addClass('show');
    });
    $headerSearchClose.on('click', function () {
        $headerSearchForm.removeClass('show');
    });

    /*--
        Side Header
    -----------------------------------*/
    var $sideHeaderToggle = $('.side-header-toggle'),
        $sideHeaderClose = $('.side-header-close'),
        $sideHeader = $('.side-header');

    /*Add/Remove Show/Hide Class On Depending on Viewport Width*/
    function $sideHeaderClassToggle() {
        var $windowWidth = $window.width();
        if ($windowWidth >= 1200) {
            $sideHeader.removeClass('hide').addClass('show');
        } else {
            $sideHeader.removeClass('show').addClass('hide');
        }
    }
    $sideHeaderClassToggle();
    /*Side Header Toggle*/
    $sideHeaderToggle.on('click', function () {
        if ($sideHeader.hasClass('show')) {
            $sideHeader.removeClass('show').addClass('hide');
        } else {
            $sideHeader.removeClass('hide').addClass('show');
        }
    });
    /*Side Header Close (Visiable Only On Mobile)*/
    $sideHeaderClose.on('click', function () {
        $sideHeader.removeClass('show').addClass('hide');
    });

    /*--
        Side Header Menu
    -----------------------------------*/
    var $sideHeaderNav = $('.side-header-menu'),
        $sideHeaderSubMenu = $sideHeaderNav.find('.side-header-sub-menu');

    /*Add Toggle Button in Off Canvas Sub Menu*/
    $sideHeaderSubMenu.siblings('a').append('<span class="menu-expand"><i class="zmdi bx bx-chevron-down"></i></span>');

    /*Close Off Canvas Sub Menu*/
    $sideHeaderSubMenu.slideUp();

    /*Category Sub Menu Toggle*/
    $sideHeaderNav.on('click', 'li a, li .menu-expand', function (e) {
        var $this = $(this);
        if ($this.parent('li').hasClass('has-sub-menu') || ($this.attr('href') === '#' || $this.hasClass('menu-expand'))) {
            e.preventDefault();
            if ($this.siblings('ul:visible').length) {
                $this.parent('li').removeClass('active').children('ul').slideUp().siblings('a').find('.menu-expand i').removeClass('bx bx-chevron-up').addClass('bx bx-chevron-down');
                $this.parent('li').siblings('li').removeClass('active').find('ul:visible').slideUp().siblings('a').find('.menu-expand i').removeClass('bx bx-chevron-up').addClass('bx bx-chevron-down');
            } else {
                $this.parent('li').addClass('active').children('ul').slideDown().siblings('a').find('.menu-expand i').removeClass('bx bx-chevron-down').addClass('bx bx-chevron-up');
                $this.parent('li').siblings('li').removeClass('active').find('ul:visible').slideUp().siblings('a').find('.menu-expand i').removeClass('bx bx-chevron-up').addClass('bx bx-chevron-down');
            }
        }
    });

    // Adding active class to nav menu depending on page
    var pageUrl = window.location.href.substr(window.location.href.lastIndexOf("/") + 1);
    $('.side-header-menu a').each(function () {
        if ($(this).attr("href") === pageUrl || $(this).attr("href") === '') {
            $(this).closest('li').addClass("active").parents('li').addClass('active').children('ul').slideDown().siblings('a').find('.menu-expand i').removeClass('bx bx-chevron-down').addClass('bx bx-chevron-up');
        }
        else if (window.location.pathname === '/' || window.location.pathname === '/index.html') {
            $('.side-header-menu a[href="index.html"]').closest('li').addClass("active").parents('li').addClass('active').children('ul').slideDown().siblings('a').find('.menu-expand i').removeClass('bx bx-chevron-down').addClass('bx bx-chevron-up');
        }
    })

    /*--
        Tooltip, Popover & Tippy Tooltip
    -----------------------------------*/
    /*Bootstrap Tooltip*/
    $('[data-toggle="tooltip"]').tooltip();
    /*Bootstrap Popover*/
    $('[data-toggle="popover"]').popover();
    /*Tippy Tooltip*/
    tippy('.tippy, [data-tippy-content], [data-tooltip]', {
        flipOnUpdate: true,
        boundary: 'window',
    });

    /*-- 
        Selectable Table
    -----------------------------------*/
    function tableSelectable() {
        var $tableSelectable = $('.table-selectable');
        $tableSelectable.find('tbody .selected').find('input[type="checkbox"]').prop('checked', true);
        $tableSelectable.on('click', 'input[type="checkbox"]', function () {
            var $this = $(this);
            if ($this.parent().parent().is('th')) {
                if (!$this.is(':checked')) {
                    $this.closest('table').find('tbody').children('tr').removeClass('selected').find('input[type="checkbox"]').prop('checked', false);
                } else {
                    $this.closest('table').find('tbody').children('tr').addClass('selected').find('input[type="checkbox"]').prop('checked', true);
                }
            } else {
                if (!$this.is(':checked')) {
                    $this.closest('tr').removeClass('selected');
                } else {
                    $this.closest('tr').addClass('selected');
                }
                if ($this.closest('tbody').children('.selected').length < $this.closest('tbody').children('tr').length) {
                    $this.closest('table').find('thead').find('input[type="checkbox"]').prop('checked', false);
                } else if ($this.closest('tbody').children('.selected').length === $this.closest('tbody').children('tr').length) {
                    $this.closest('table').find('thead').find('input[type="checkbox"]').prop('checked', true);
                }
            }
        });
    }
    tableSelectable();


    // Common Resize function
    function resize() {
        $sideHeaderClassToggle();
    }
    // Resize Window Resize
    $window.on('resize', function () {
        resize();
    });

    /*--
        Custom Scrollbar (Perfect Scrollbar)
    -----------------------------------*/
    $('.custom-scroll').each(function () {
        var ps = new PerfectScrollbar($(this)[0]);
    });

})(jQuery);

