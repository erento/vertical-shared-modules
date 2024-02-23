const eventEmitter = $({});

function handleResize() {
    var currentWidth = $(window).width();
    var initialResize = false;
    var windowWidth = currentWidth;
    var isMobile = false;
    var isTablet = false;
    var isAtLeastDesktop = false;
    var isMobileOrTablet = false;

    // Check only for X axis resizing
    if (currentWidth != windowWidth || !initialResize) {
        initialResize = true;
        windowWidth = currentWidth;
        
        isMobile = currentWidth < 768;
        isTablet = currentWidth >= 768 && currentWidth < 992;
        isMobileOrTablet = currentWidth < 992;
        isAtLeastDesktop = currentWidth > 991;
        
        eventEmitter.trigger('onWindowXResize', {
            isMobile: isMobile,
            isTablet: isTablet,
            isAtLeastDesktop: isAtLeastDesktop,
            isMobileOrTablet: isMobileOrTablet
        });
    }
}

$(window).on("resize", handleResize);

export function subscribeToResize(callback) {
    eventEmitter.on('onWindowXResize', callback);
}
