export function LoadingDotsModule() {
    var loading_dots_html = '<div class="loading-dots-animation"><div class="dot"></div><div class="dot"></div><div class="dot"></div></div>';

    function animateLoadingDotsBtn(btn) {
        if (!btn.hasClass('loading-dots-applied')) {
            btn.addClass('loading-dots-applied');
            btn.children().css('visibility', 'hidden');
            btn.append(loading_dots_html);
        }
    }

    $('.loading-dots').click(function(){
        animateLoadingDotsBtn($(this));
    });

    function hideLoadingDots() {
        $('.loading-dots-animation').remove()
        $('.loading-dots').removeClass('loading-dots-applied');
        $('.loading-dots').children().css('visibility', 'visible');
    }

    // clear all loading-dots-animation when going back in history
    window.addEventListener('pagehide', () => {
        hideLoadingDots();
    });

    return {
        hideLoadingDots,
        animateLoadingDotsBtn
    }
}
