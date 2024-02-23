export function ReadMoreModule() {
    function calculateReadMoreTextContainers() {
        $('read-more-text-container-component').each(function () {
            var paragraph = $(this).find('.paragraph')[0];
            if (paragraph.offsetHeight < paragraph.scrollHeight) {
                $(this).addClass('expandable');
                $(this).find('.read-more-btn').show();
            }
            var scrollTop = $(window).scrollTop();
            var containerOffset = $(this).offset().top;
            $(this).data('windowPosition', scrollTop - containerOffset);
        });
    }

    $(document).on('click', 'read-more-text-container-component .read-more-btn', function () {
        var container = $(this).closest('read-more-text-container-component');
        container.addClass('expanded');
        container.find('.show-less-btn').show();
        
        var scrollTop = $(window).scrollTop();
        var containerOffset = container.offset().top;
        container.data('windowPosition', scrollTop - containerOffset);
        $(this).hide();
    });

    $(document).on('click', 'read-more-text-container-component .show-less-btn', function () {
        var container = $(this).closest('read-more-text-container-component');
        container.removeClass('expanded');
        container.find('.read-more-btn').show();
        $(this).hide();

        var windowPosition = container.data('windowPosition');
        var containerOffset = container.offset().top;
        $(window).scrollTop(windowPosition + containerOffset);
    });

    return {
        calculateReadMoreTextContainers
    };
}
