export function FeaturedItemsModule() {
    const featuredItemsFlickityGalleries = [];
    const featuredItems = document.querySelectorAll("featured-item-component");

    if (featuredItems.length > 0) {
        featuredItems.forEach(function(featuredItem, index) {
            const featuredItemsFlickityGalleryInstance = $(featuredItem).find('flickity-gallery-component').flickity({
                cellAlign: 'left',
                contain: true,
                lazyLoad: 1,
                pageDots: true,
                prevNextButtons: true,
                wrapAround: false,
                setGallerySize: false,
                selectedAttraction: 0.2,
                friction: 0.8,
                on: {
                    dragStart: function() {
                        this.slider.style.pointerEvents = 'none';
                    },
                    dragEnd: function() {
                        this.slider.style.pointerEvents = 'auto';
                    }
                }
            });

            featuredItemsFlickityGalleries.push(featuredItemsFlickityGalleryInstance);
        });

        $('featured-items-component .show-more-btn').click(function() {
            const featuredItemsHidden = $("horizontal-scroller-item.desktop-hidden");
            const showMoreFeaturedItemsBtn = $(this);

            featuredItemsHidden.each(function(index, element) {
                if (index < 6) {
                    $(element).hide().removeClass('desktop-hidden').fadeIn();
                }
            });
    
            if (featuredItemsHidden.length < 7) {
                showMoreFeaturedItemsBtn.remove();
            }
    
            featuredItemsFlickityGalleries.forEach(function(flickityGallery) {
                flickityGallery.flickity('resize');
            });
        });
    }
}
