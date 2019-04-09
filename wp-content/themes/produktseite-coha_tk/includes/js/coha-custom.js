var CoHa = {
    settings: {
        scroll: {
            shrinkStartAtY: 1100,
            shrinkEndAtY: 250,

            // shrinkStartAtScrolls: 50,
            shrinkStartAtScrolls: 100,
            // shrinkEndAtScrolls: 25,
            shrinkEndAtScrolls: 25,
        },
    },
    body: jQuery('body'),
    window: jQuery(window),
    classes: {
        scrolledDown : 'scrolled-down',
        scrolledUp: 'scrolled-up',
        shrinked: 'shrinked',
    },
    functions: {},
};

var y = 0;
var lastY = 0;
var scrollingUp = true;
var scrollingDown = !scrollingUp;
var scrollUps = 0;
var scrollDowns = 0;

// jQuery Alias
(function($) {

    y = CoHa.window.scrollTop();
    lastY = y;

    // Define Scroll Function
    CoHa.functions.scroll = function() {
        var _body = jQuery('body');
        var _navbar = jQuery('.navbar');
        var _classes = CoHa.classes;
        var _settings = CoHa.settings;
        var baseHeight = 120;

        y = CoHa.window.scrollTop();
        if(lastY == 0) { lastY = y; }

        scrollingUp = y < lastY;
        scrollingDown = !scrollingUp;

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
                console.log('shrink', y, lastY);

                // Scrolling Down
                scrollDowns = y - lastY;
                scrollUps = 0;
                if(y > _settings.scroll.shrinkStartAtY && scrollDowns > _settings.scroll.shrinkStartAtScrolls) {
                    // Start Shrinking
                    if(!_navbar.hasClass(_classes.shrinked)) {
                        _navbar.addClass(_classes.shrinked);

                        // Reset LastY
                    }
                    lastY = y;

                }

            } else if(scrollingUp) {
                // If Scrolling up
                scrollUps = lastY - y;
                scrollDowns = 0;
                if(scrollUps > _settings.scroll.shrinkEndAtScrolls || y < _settings.scroll.shrinkEndAtY) {
                    // Unshrink
                    if(_navbar.hasClass(_classes.shrinked)) {
                        _navbar.removeClass(_classes.shrinked);
                        // Reset LastY
                        
                    }
                    lastY = y;

                }
            }

            console.log(y, lastY);
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