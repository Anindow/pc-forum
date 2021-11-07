$(function () {
    "use strict";

    // ==============================================================
    // Get the current year for copyright
    // ==============================================================
    $('#year').text(new Date().getFullYear());

    // ==============================================================
    // Bootstrap-switch
    // ==============================================================

    $("input[data-bootstrap-switch]").each(function(){
        $(this).bootstrapSwitch('state', $(this).prop('checked'));
    });

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

    // ==============================================================
    // tooltip
    // ==============================================================
    $('.tooltips').tooltip({
        selector: "[data-toggle=tooltip]",
        container: "body"
    })
    // ==============================================================
    // Popover
    // ==============================================================
    $(function () {
        $('[data-toggle="popover"]').popover()
    })
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
    })

});
