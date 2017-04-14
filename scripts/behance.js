$(document).ready(function() {
    $(window).on("scroll touchmove", function () {
        $('#logonav').toggleClass('tiny', $(document).scrollTop() > 140);
        $('nav li a').toggleClass('tiny', $(document).scrollTop() > 140);
    });
}); // end ready