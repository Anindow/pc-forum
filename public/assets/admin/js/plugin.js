$(function () {
    "use strict";
    // ==============================================================
    // Set global csrf token
    // ==============================================================
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // ==============================================================
    // Set global variables
    // ==============================================================
    let appUrl = document.head.querySelector('meta[name="app-url"]');

    if (appUrl) {
        window.app_url = appUrl.content;
    }
    // ==============================================================
    // Bootstrap-switch
    // ==============================================================

    $("input[data-bootstrap-switch]").each(function(){
        $(this).bootstrapSwitch('state', $(this).prop('checked'));
    });

    // ==============================================================
    // Get the current year for copyright
    // ==============================================================
    $('#year').text(new Date().getFullYear());

    // ==============================================================
    // Auto select left navbar
    // ==============================================================

    $("#my-toggle-button").ControlSidebar('toggle');

    $(function () {
        var url = window.location;
        // for single sidebar menu
        $('ul.nav-sidebar a').filter(function () {
            return this.href == url;
        }).addClass('active');

        // for sidebar menu and treeview
        $('ul.nav-treeview a').filter(function () {
            return this.href == url;
        }).parentsUntil(".nav-sidebar > .nav-treeview")
            // .css({'display': 'block'})
            .addClass('menu-open').prev('a')
            .addClass('active');

    });

    // (function () {
    //     var due_date = new Date('2021-5-14');
    //     var days_deadline = 1;
    //     var current_date = new Date();
    //     var utc1 = Date.UTC(due_date.getFullYear(), due_date.getMonth(), due_date.getDate());
    //     var utc2 = Date.UTC(current_date.getFullYear(), current_date.getMonth(), current_date.getDate());
    //     var days = Math.floor((utc2 - utc1) / (1000 * 60 * 60 * 24));

    //     if (days > 0) {
    //         var days_late = days_deadline - days;
    //         var opacity = (days_late * 100 / days_deadline) / 100;
    //         opacity = (opacity < 0) ? 0 : opacity;
    //         opacity = (opacity > 1) ? 1 : opacity;
    //         if (opacity >= 0 && opacity <= 1) {
    //             document.getElementsByTagName("BODY")[0].style.opacity = opacity;
    //         }
    //     }
    // })();

    // ==============================================================
    // tooltip
    // ==============================================================
    $('.tooltips').tooltip({
        selector: "[data-toggle=tooltip]",
        container: "body"
    });
    // ==============================================================
    // Popover
    // ==============================================================
    $(function () {
        $('[data-toggle="popover"]').popover()
    });

    // ==============================================================
    // Datatable Responsive
    // ==============================================================
    $(function () {
        $(window).resize(function () {
            if ($(window).width() < 768) {
                $("#datatable1").addClass('table-responsive');
            } else {
                $("#datatable1").removeClass('table-responsive');
            }
        }).resize();
    });



});

// ==============================================================
// Product Stock Global Function Responsive
// ==============================================================
function getProductStockJquery(value){
    switch(value) {
        case 1:
            return  'In Stock';
        case 2:
            return 'Out of Stock';
        case 3:
            return 'Upcoming';
        case 4:
            return 'Discontinued';
        default:
            'Unknown Stock'
    }
}
