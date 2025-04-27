var uriPath = location.pathname;
var targetLocation = uriPath.split('/').at(-1);

$(document).ready(function() {
    if (targetLocation == "tracker-map.php") {
        hideHamburgerMenuIcon();
    } else {
        // do nothing...
    }

    function hideHamburgerMenuIcon() {
        $("body").toggleClass("sidebar-toggled");
        $(".sidebar").toggleClass("toggled");
        
        if ($(".sidebar").hasClass("toggled")) {
            $('.sidebar .collapse').collapse('hide');
        };
    }
});