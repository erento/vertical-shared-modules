export function HeaderModule() {

    var mobileMenu = $('.mobile-menu-wrapper');

    function lockBodyScroll() {
        $('html').addClass('no-scroll');
    }

    function unlockBodyScroll() {
        $('html').removeClass('no-scroll');
    }

    function closeMobileMenu(){
        $(mobileMenu).removeClass('opened');
        unlockBodyScroll();
    }

    function openMobileMenu(){
        $(mobileMenu).addClass('opened addTransitions');
        lockBodyScroll();
    }

    $('.hamburger').click(function(){ openMobileMenu() });
    $('.close-mobile-menu, .mobile-menu-overlay').click(function(){ closeMobileMenu() });

    $('.toggle-mobile-contact-info').click(function(){
        var contactContainer = $('.mobile-contact');

        if ($(contactContainer).hasClass('expanded')) {
            $(contactContainer).removeClass('expanded');
        } else {
            $(contactContainer).addClass('expanded');
        }
    });

    $('.mobile-menu .menu-item-has-children').click(function(){
        if ($(this).hasClass('expanded')) {
            $(this).removeClass('expanded');
        } else {
            $(this).addClass('expanded');
        }
    });

    $('.mobile-menu .menu-item-has-children .sub-menu .menu-item').click(function(e){
        e.stopPropagation();
    });
   
    return {
        lockBodyScroll,
        unlockBodyScroll,
        closeMobileMenu,
        openMobileMenu
    };
}
