var CoHa = {
    settings: {
        scroll: {
            shrinkStartAtY: 700,
            shrinkEndAtY: 250,

            // shrinkStartAtScrolls: 50,
            shrinkStartAtScrolls: 10,
            // shrinkEndAtScrolls: 25,
            shrinkEndAtScrolls: 50,
        },
    },
    body: jQuery('body'),
    window: jQuery(window),
    classes: {
        scrolledDown : 'scrolled-down',
        scrolledUp: 'scrolled-up',
        shrinked: 'shrinked',
    },
    functions: {

    },
};
var lastY = 0;
var scrollUps = 0;
var scrollDowns = 0;

// jQuery Alias
(function($) {

    // Define Scroll Function
    CoHa.functions.scroll = function() {
        var _body = jQuery('body');
        var _navbar = jQuery('.navbar');
        var _classes = CoHa.classes;
        var _settings = CoHa.settings;
        var baseHeight = 120;
        var y = CoHa.window.scrollTop();

        var scrollingUp = y < lastY;
        var scrollingDown = !scrollingUp;

        // Its Top
        if(y <= 0) {
            _body.removeClass(_classes.scrolledDown);
            _body.addClass(_classes.scrolledUp);

        } 
        // scrolled down a bit
        else {
            if(!_body.hasClass(_classes.scrolledDown)) {
                _body.addClass(_classes.scrolledDown);
            }

            if(_body.hasClass(_classes.scrolledUp)) {
                _body.removeClass(_classes.scrolledUp);
            }

            if(scrollingDown) {
                // Scrolling Down
                scrollDowns += 1;
                scrollUps = 0;
                if(y >= _settings.scroll.shrinkStartAtY && scrollDowns > _settings.scroll.shrinkStartAtScrolls) {
                    // Start Shrinking
                    if(!_navbar.hasClass(_classes.shrinked)) {
                        _navbar.addClass(_classes.shrinked);
                    }
                }
            } else if(scrollingUp) {
                // If Scrolling up
                scrollUps += 1;
                scrollDowns = 0;
                if(scrollUps > _settings.scroll.shrinkEndAtScrolls || y < _settings.scroll.shrinkEndAtY) {
                    // Unshrink
                    if(_navbar.hasClass(_classes.shrinked)) {
                        _navbar.removeClass(_classes.shrinked);
                    }
                }
            }  
        }

        lastY = y;

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