/* ************** */
/* Magnific Popup */
/* ************** */

$(document).ready(function() {
    $(".fancybox").fancybox({
        prevEffect		: 'none',
        nextEffect		: 'none',
        closeBtn		: false,
        autoPlay        : true,
        helpers		: {
            title	: { type : 'inside' },
            buttons	: {}
        }
    });
});