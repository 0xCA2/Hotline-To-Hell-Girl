$(window).bind("load", function () {
	attach_footer();
});	

function attach_footer() {
    var footer = $("#fwrapper");
    var pos = footer.position();
    var height = $(window).height();
    height = height - pos.top;
    height = height - footer.height();
    if (height > 0) {
        footer.css({
            'margin-top': height + 'px'
        });
    }
}

var resizeTimer;

//Event to handle resizing
$(window).resize(function () 
{
  clearTimeout(resizeTimer);
  resizeTimer = setTimeout(Resized, 100);
});

//Actual Resizing Event
function Resized() 
{
   attach_footer();
};
