var CoHa = {
    body: jQuery('body'),
    window: jQuery(window),
    functions: {

    },
};

var classScrolling = 'scrolling-down';
var classNotScrolling = 'scrolled-up';

// jQuery Alias
(function($) {

    // Define Scroll Function
    CoHa.functions.scroll = function() {
        var _body = jQuery('body');
        var _navbar = jQuery('.navbar');
        var baseHeight = 120;
        var y = CoHa.window.scrollTop();

        // Its Top
        if(y >= 0) {
            _body.removeClass(classScrolling);
            _body.addClass(classNotScrolling);
            
        } 
        // scrolled down a bit
        else {
            if(!_body.hasClass(classScrolling)) {
                _body.addClass(classScrolling);
            }

            if(_body.hasClass(classNotScrolling)) {
                _body.removeClass(classNotScrolling);
            }

        }

    }

    // Set Function to CoHa Window
    CoHa.window.on('scroll', function (e){
        CoHa.functions.scroll();
    });

    // Document Ready
    $(document).ready(function() {
        // Start first Scroll Function
        CoHa.functions.scroll();
    });

})(jQuery);