export function HorizontalScrollerModule() {
    if ($('.horizontal-scroller-with-controls').length > 0) {
        var horizontalScrollerComponent     = $('.horizontal-scroller-with-controls');
        var horizontalScroller              = horizontalScrollerComponent.find('horizontal-scroller');
        var horizontalScrollNextBtn         = horizontalScrollerComponent.find('.btn.next');
        var horizontalScrollPrevBtn         = horizontalScrollerComponent.find('.btn.prev');
        var horizontalScrollCardPadding     = parseInt(horizontalScrollerComponent.find('horizontal-scroller-item').eq(2).css('padding-left'));
        var horizontalScrollFactor          = horizontalScrollerComponent.find('.location-cards-wrapper').innerWidth() + horizontalScrollCardPadding;

        function scrollToRight(){
            horizontalScroller.animate({
                scrollLeft: horizontalScroller.scrollLeft() + horizontalScrollFactor
            }, 300);

            if (horizontalScroller.scrollLeft() + horizontalScrollFactor > 0) horizontalScrollPrevBtn.removeClass('disabled');
            if (horizontalScroller.scrollLeft() + horizontalScroller.innerWidth() + horizontalScrollFactor >= horizontalScroller[0].scrollWidth) horizontalScrollNextBtn.addClass('disabled');
        }

        function scrollToLeft(){
            horizontalScroller.animate({
                scrollLeft: horizontalScroller.scrollLeft() - horizontalScrollFactor
            }, 300);

            if (horizontalScroller.scrollLeft() - horizontalScrollFactor <= 0) horizontalScrollPrevBtn.addClass('disabled');
            if (horizontalScroller.scrollLeft() + horizontalScroller.innerWidth() - horizontalScrollFactor < horizontalScroller[0].scrollWidth) horizontalScrollNextBtn.removeClass('disabled');
        }

        $(horizontalScrollNextBtn).click(function(){
            scrollToRight();
        });

        $(horizontalScrollPrevBtn).click(function(){
            scrollToLeft();
        });
    }
}
