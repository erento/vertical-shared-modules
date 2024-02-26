export function MiscModule() {    
    // scroll to how it works section
    $('.scroll-to-how-it-works a').click(function () {
        if ($(mobileMenu).hasClass('opened')) closeMobileMenu();

        $([document.documentElement, document.body]).animate({
            scrollTop: $("#how-it-works").offset().top
        }, 600);
    });
}
