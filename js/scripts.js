jQuery(document).ready(function ($) {
    $( ".sidebar-toggle" ).click(function() {
        var $n = $("#sidebar-page").css("left");
        if($n != '0px'){
            $("#sidebar-page").animate({
                    left: 0
                },
                "slow",
                function () {
                    // Animation complete.
                    $("#sidebar-page").css({position: 'relative'});
                    //$( ".sidebar-toggle" ).find('.button-text').html('Close Sidebar');
                    $( ".sidebar-toggle" ).find('.fa').removeClass( "fa-angle-double-right" ).addClass("fa-angle-double-left");
                });
        } else {
            $("#sidebar-page").css({position: 'absolute'}).animate({
                    left: '-999px'
                },
                "slow",
                function () {
                    // Animation complete.
                    //$( ".sidebar-toggle" ).find('.button-text').html('Open Sidebar');
                    $( ".sidebar-toggle" ).find('.fa').removeClass( "fa-angle-double-left" ).addClass("fa-angle-double-right");
                });
        };
    });
});
