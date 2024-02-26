export function SerpModule() {
    $('.mobile-search-results-box').on('click', function () {
        $('search-box-component').addClass('mobile-opened');
        $('header').addClass('mobile-searchbox-popup-opened');
    });

    function closeMobileSERPSearchBox() {
        $('search-box-component').removeClass('mobile-opened');
        $('header').removeClass('mobile-searchbox-popup-opened');
    }

    $('.mobile-overlay').on('click', function () {
        closeMobileSERPSearchBox();
    });

    $('search-box-component .mobile-controls .close').on('click', function () {
        closeMobileSERPSearchBox();
    });

    // Set page of pagination to center on mobile
    if ($('.serp-pagination').length > 0) {
        var active_page = $('.serp-pagination .page.active');
        var active_page_count = active_page.html();
        var page_width = active_page.outerWidth(true);
        var pagination = $('.serp-pagination .pages');
        var pagination_width = pagination.innerWidth();

        var scroll_pagination = ((active_page_count * page_width) - (pagination_width / 2));
        pagination.scrollLeft(scroll_pagination);
    }
}
