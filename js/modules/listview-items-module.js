export function ListviewItemsModule() {
    const listviewItemsFlickityGalleries = [];
    const listviewItems = document.querySelectorAll("listview-item-component");
    
    if (listviewItems.length > 0) {
        listviewItems.forEach(function(listviewItem, index) {
            const flickityGallery = $(listviewItem).find('flickity-gallery-component').flickity({
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

            // Listen for the change event to remove lazy load on next slide
            flickityGallery.on('change.flickity', function(event, index) {
                let nextSlide = $(this).find('.flickity-slider .slide').eq(index + 1);
                if (nextSlide.length > 0 && nextSlide.find('img')) {
                    nextSlide.find('img').removeAttr('loading');
                }
            });

            listviewItemsFlickityGalleries.push(flickityGallery);
        });
    }
}
