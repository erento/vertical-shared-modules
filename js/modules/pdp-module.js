import { HeaderModule } from './header-module.js';

export function PdpModule(
    site_url,
    loadingDotsModule,
    subscribeToResize
) {    
    const headerModule = HeaderModule();
    
    var isAtLeastDesktop = false;
    var isMobileOrTablet = false;
    var isMobileEnquiryOpen = false;
    var fullscreenGalleryCloned = false;
    var isFullscreenGalleryDesktop = false;
    var isFullscreenGalleryOpened = false;
    var createFullscreenGallery = false;
    var main_gallery_flkty, main_gallery_thumbs_flkty, fullscreen_gallery_flkty, fullscreen_gallery_flkty_options;
    var isMobileEnquryCloned = false;
    var scrollPositionBeforeMobileEnqOpen = 0;

    subscribeToResize(function(event, data) {
        isAtLeastDesktop = data.isAtLeastDesktop;
        isMobileOrTablet = data.isMobileOrTablet;
        closeAllCustomDropdowns();
        closeAllDatepickers();
        rerenderPdpFullscreenGallery();

        if (isMobileEnquiryOpen) {
            closeMobileEnquiry();
        }
    });

    // PDP mobile enquiry component
    function openMobileEnquiry() {
        if (!isMobileEnquryCloned) {
            $('mobile-enquiry-component').appendTo('.fullscreen-modal');
            isMobileEnquryCloned = true;
        }

        scrollPositionBeforeMobileEnqOpen = window.pageYOffset;
        $('.fullscreen-modal').addClass('visible');
        $('mobile-enquiry-component').show();
        window.scrollTo(0, 0);
        $('.website-container').hide();
        isMobileEnquiryOpen = true;
    }

    function closeMobileEnquiry() {
        $('.fullscreen-modal').removeClass('visible');
        $('mobile-enquiry-component').hide();
        $('.website-container').show();
        window.scrollTo(0, scrollPositionBeforeMobileEnqOpen);
        isMobileEnquiryOpen = false;
    }

    function formatDateForDisplay(date) {
        var monthsDeArray = ['Jan', 'Feb', 'Mär', 'Apr', 'Mai', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dez'];
        var d = date.getDate();
        var m = monthsDeArray[date.getMonth()];
        var y = date.getFullYear();
        return m + ' ' + d + ', ' + y;
    }

    function formatDateForNative(date) {
        var month = '' + (date.getMonth() + 1);
        var day = '' + date.getDate();
        var year = date.getFullYear();

        if (month.length < 2)
            month = '0' + month;
        if (day.length < 2)
            day = '0' + day;

        return [year, month, day].join('-');
    }

    // Native date & time picker handling
    function updateDateOrTimeLabelValue(thisObj) {
        const valueElement = thisObj.parent().find('.dh-item-value');
        const newValue = thisObj.val();

        if (thisObj.attr('type') == 'date') {
            const newDate = formatDateForDisplay(new Date(newValue));
            valueElement.attr('value', newDate);
        } else if (thisObj.attr('type') == 'time') {
            valueElement.attr('value', newValue);
        }
    }

    function closeAllCustomDropdowns() {
        $('.custom-select-wrapper').hide();
        $('.dh-item-value').removeClass('selected');
        document.removeEventListener('mousedown', closeCustomDropdown);
    }

    function closeCustomDropdown(event) {
        if (event.target.classList.contains('custom-select-option')) {
            const selectedHour = event.target.innerHTML;
            const dropdown = event.target.parentElement;

            $(dropdown).find('.custom-select-option').removeClass('selected');
            event.target.classList.add('selected');
            $(dropdown).parents('.dh-item').find('.dh-item-value').attr('value', selectedHour);

            if ($(dropdown).parents('.dh-item.pickup-hour').length) updateDeliveryHourDropdown();
        }

        closeAllCustomDropdowns();
    }

    function openCustomDropdown(thisObj) {
        var customDropdownWrapper = thisObj.parent().find('.custom-select-wrapper');

        if (customDropdownWrapper.length > 0) {
            thisObj.addClass('selected');
            customDropdownWrapper.show();

            var customDropdown = customDropdownWrapper.find('.custom-select');
            var selectedItem = customDropdown.find('.custom-select-option.selected');
            var selectedItemTopOffset = customDropdown.scrollTop() + selectedItem.position().top;
            var centerOffset = selectedItemTopOffset - customDropdown.height() / 2;
            customDropdown.scrollTop(centerOffset);

            document.addEventListener('mousedown', closeCustomDropdown);
        }
    }

    function setDatesToClientSideTimezone() {
        var date = new Date();
        date.setDate(date.getDate() + 1);
        var tomorrowDateNative = formatDateForNative(date);
        var tomorrowDateFormatted = formatDateForDisplay(date);

        date.setDate(date.getDate() + 1);
        var afterTomorrowDateNative = formatDateForNative(date);
        var afterTomorrowDateFormatted = formatDateForDisplay(date);

        $('.sidebar .pickup-date .dh-item-native').attr({'min': tomorrowDateNative, 'value': tomorrowDateNative});
        $('.sidebar .pickup-date .dh-item-value').attr('value', tomorrowDateFormatted);

        $('.sidebar .delivery-date .dh-item-native').attr({'min': tomorrowDateNative, 'value': afterTomorrowDateNative});
        $('.sidebar .delivery-date .dh-item-value').attr('value', afterTomorrowDateFormatted);
    }

    function closeAllDatepickers() {
        $('.sidebar .js-datepicker').datepicker('hide');
    }

    function isSameDaySelected() {
        var sameDay = false;
        var pickupDate = pickupDatepicker.datepicker('getDate');
        var deliveryDate = deliveryDatepicker.datepicker('getDate');

        if (pickupDate.getTime() === deliveryDate.getTime()) {
            sameDay = true;
        }

        return sameDay;
    }

    var deliveryDropdownAltered = false;
    function updateDeliveryHourDropdown() {
        var newHour;
        var pickupHour;
        var sameDay = isSameDaySelected();

        if (sameDay) {
            pickupHour = $('.sidebar .pickup-hour .custom-select-option.selected').index();
            deliveryDropdownAltered = true;
        } else {
            deliveryDropdownAltered = false;
        }

        var deliveryDropdown = $('.sidebar .delivery-hour .custom-select');
        deliveryDropdown.find('.custom-select-option').removeClass('disabled');

        if (deliveryDropdownAltered) {
            var isPickupAfterDelivery = false;
            var deliveryHour = $('.sidebar .delivery-hour .custom-select-option.selected').index();

            if (pickupHour >= deliveryHour) isPickupAfterDelivery = true;

            deliveryDropdown.children().each(function (index, element) {
                if (index < pickupHour + 1) $(element).addClass('disabled');
                if (index == pickupHour + 1) {
                    if (isPickupAfterDelivery) {
                        deliveryDropdown.children().removeClass('selected');
                        $(element).addClass('selected');
                        newHour = $(element).html();
                    }
                }
            });

            if (isPickupAfterDelivery) $('.sidebar .delivery-hour .dh-item-value.hour').attr('value', newHour);
        }
    }

    function setErrorsUnderFields(key, value) {
        var className = '.' + key;
        $(submittingForm).find(className).append(value);

        if (key != 'ss-error-general')
            $(submittingForm).find(className).parent().find('input').addClass('error');
    }

    function clearErrorsUnderFields() {
        $('.ss-error').empty();
        $('.ss-error').parent().find('input').removeClass('error');
    }

    function showSuccessSubmit() {
        $('.hide-after-enq-submit').hide();
        $('.fill-customer-email').html(customerEmail);
        $('enquiry-box-component .success-container').show();
        $('.floating-bottom-bar').addClass('enquiry-submitted');

        if (isAtLeastDesktop) {
            $([document.documentElement, document.body]).animate({
                scrollTop: $(".sidebar .enquiry-box").offset().top
            }, 600);
        }
    }

    $('form.enquiry-form input').on('input', function(){
        $(this).removeClass('error');
        $(this).parent().find('.ss-error').empty();
    })

    function trackBackendPdpClick(btnType, device) {
        $.ajax({
            url : site_url + '/wp-json/theme/v1/be_track',
            type : 'GET',
            data : {
                'type' : btnType,
                'device' : device,
                'item_id' : $('#item_id').text(),
                'location' : selectedLocation
            },
            dataType:'json',
            success : function(data) {},
            error : function(request, error) {
                console.log("Request: " + JSON.stringify(request));
            }
        });
    }

    var submittingForm;
    var customerEmail;
    var submittingEnqForm = false;
    function formSubmit(formType) {
        if (!submittingEnqForm) {
            if (formType == 'desktop') submittingForm = $('.sidebar form.enquiry-form');
            else submittingForm = $('mobile-enquiry-component form.enquiry-form');

            clearErrorsUnderFields();
            submittingEnqForm = true;
            customerEmail = submittingForm.find('input[name="email"]').val();

            $.ajax({
                url : site_url + '/wp-json/theme/v1/enquiry-submit',
                type : 'GET',
                data : submittingForm.serialize(),
                dataType:'json',
                success : function(data) {
                    if (Object.getPrototypeOf(data) == '[object Object]') {
                        for (const [key, value] of Object.entries(data.message)) {
                            let errorValue = value;
                            if (Array.isArray(value)) {
                                errorValue = value.join("<br>");
                            }
                            setErrorsUnderFields(key, errorValue);
                        }
                    } else {
                        trackBackendPdpClick('pdp_enquiry_btn', formType);
                        showSuccessSubmit();
                    }
                    submittingEnqForm = false;
                    loadingDotsModule.hideLoadingDots();
                },
                error : function(request, error) {
                    trackBackendPdpClick('pdp_enquiry_btn', formType);
                    setErrorsUnderFields('ss-error-general', 'Beim Senden der Anfrage ist etwas ist schiefgelaufen. Bitte versuche es erneut.');
                    submittingEnqForm = false;
                    console.log("Request: " + JSON.stringify(request));
                }
            });
        }
    }

    var desktop_fullscreen_fklty_options = {
        selectedAttraction: 1,
        friction: 1,
        draggable: false,
    }

    function createFullscreenGalleryFlickity() {
        fullscreen_gallery_flkty_options = {
            cellAlign: 'left',
            contain: true,
            lazyLoad: 1,
            pageDots: false,
            prevNextButtons: true,
            wrapAround: false,
            setGallerySize: false,
            on: {
                change: function( index ) {
                    updateFullscreengalleryIndex(index);
                }
            }
        };

        if (isAtLeastDesktop) {
            Object.assign(fullscreen_gallery_flkty_options, desktop_fullscreen_fklty_options);
            isFullscreenGalleryDesktop = true;
        }

        fullscreen_gallery_flkty = $('.fullscreen-gallery-slides').flickity(fullscreen_gallery_flkty_options);
        createFullscreenGallery = false;
    }

    function cloneMainGallery() {
        var flickity_wrapper = $('.main-gallery-container .flickity-slider');
        var fullscreen_gallery = $('.fullscreen-gallery-slides');
        var slide_div = $('<div class="slide"></div>');
        flickity_wrapper.children('.slide').each(function () {
            slide_div.clone().appendTo(fullscreen_gallery).append($(this).children('img').clone());
        });

        createFullscreenGalleryFlickity();
        fullscreenGalleryCloned = true;
    }

    function rerenderPdpFullscreenGallery() {
        if (typeof fullscreen_gallery_flkty !== "undefined" && fullscreen_gallery_flkty.data('flickity') !== "undefined") {
            if (isFullscreenGalleryDesktop == isMobileOrTablet) {
                isFullscreenGalleryDesktop = !isMobileOrTablet;
                if (fullscreen_gallery_flkty.data('flickity') !== "undefined") {
                    fullscreen_gallery_flkty.flickity('destroy');
                }

                if (isFullscreenGalleryOpened) {
                    createFullscreenGalleryFlickity();
                } else {
                    createFullscreenGallery = true;
                }
            }
        }
    }

    function updateFullscreengalleryIndex(index) {
        var flkty_data = fullscreen_gallery_flkty.data('flickity');
        var slides_count = flkty_data.slides.length;

        $('.gallery-header .current-slide').html(index + 1);
        $('.gallery-header .all-slides').html(slides_count);
    }

    function fullscreenGalleryNext() {
        fullscreen_gallery_flkty.flickity('next');
    }

    function fullscreenGalleryPrevious() {
        fullscreen_gallery_flkty.flickity('previous');
    }

    function fullscreenGalleryKeys(e) {
        switch(e.which) {
            case 37: // left
            fullscreenGalleryPrevious();
            break;

            case 39: // right
            fullscreenGalleryNext();
            break;

            case 27: // escape
            closeFullscreenGallery();
            break;

            default: return; // exit this handler for other keys
        }
        e.preventDefault();
    }

    function enableFullscreenGalleryKeys() {
        document.addEventListener("keydown", fullscreenGalleryKeys);
    }

    function disableFullscreenGalleryKeys() {
        document.removeEventListener("keydown", fullscreenGalleryKeys);
    }

    function openFullscreenGallery(thumbID) {
        var index = parseInt(thumbID);
        isFullscreenGalleryOpened = true;
        $('.fullscreen-gallery').css('display', 'flex');

        if (!fullscreenGalleryCloned) {
            cloneMainGallery();
            createFullscreenGallery = true;
        }
        if (createFullscreenGallery) {
            createFullscreenGalleryFlickity();
        } else {
            fullscreen_gallery_flkty.flickity('resize');
        }

        $('.fullscreen-gallery-slides').flickity( 'select', index, false, true );
        updateFullscreengalleryIndex(index);
        headerModule.lockBodyScroll();
        enableFullscreenGalleryKeys();
    }

    function closeFullscreenGallery() {
        isFullscreenGalleryOpened = false;
        var selected_index = fullscreen_gallery_flkty.data('flickity').selectedIndex;
        main_gallery_flkty.flickity( 'select', selected_index, false, true );
        $('.fullscreen-gallery').css('display', 'none');
        headerModule.unlockBodyScroll();
        disableFullscreenGalleryKeys();
    }

    setDatesToClientSideTimezone();

    var datepickersOptions = {
        dateFormat: 'M d, yy',
        monthNamesShort: ['Jan', 'Feb', 'Mär', 'Apr', 'Mai', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dez'],
        monthNames: ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'],
        minDate: '+1d',
        maxDate: '+366d',
        beforeShow: function(input, inst) {
            inst.dpDiv.css({
                marginLeft: (-input.offsetWidth/2) + 'px',
                "z-index": 250
            });
            input.classList.add('datepicker-opened');
        }
    }

    var openDeliveryDatepicker = false;
    var datepickerPickupFunctions = {
        onSelect: function(selectedDate) {
            deliveryDatepicker.datepicker('option', 'minDate', selectedDate);
            updateDeliveryHourDropdown();
            openDeliveryDatepicker = true;
        },
        onClose: function() {
            $('.dh-item-value.js-datepicker-pickup').removeClass('datepicker-opened');

            if (openDeliveryDatepicker) {
                openDeliveryDatepicker = false;
                deliveryDatepicker.datepicker('show');
            }
        }
    }

    var datepickerDeliveryFunctions = {
        beforeShowDay: function(deliveryDate){
            var pickupDate = pickupDatepicker.datepicker('getDate');

            if (deliveryDate.getTime() === pickupDate.getTime()) return [true, "pickup-highlight"];
            else return [true];
        },
        onSelect: function() {
            updateDeliveryHourDropdown();
        },
        onClose: function() {
            $('.dh-item-value.js-datepicker-delivery').removeClass('datepicker-opened');
        }
    }

    var deliveryDatepicker = $('.sidebar .js-datepicker-delivery').datepicker(Object.assign(datepickersOptions, datepickerDeliveryFunctions));
    var pickupDatepicker = $('.sidebar .js-datepicker-pickup').datepicker(Object.assign(datepickersOptions, datepickerPickupFunctions));

    $('.open-mobile-enquiry').click(function(){
        openMobileEnquiry();
    });

    $('.close-mobile-enquiry').click(function(){
        closeMobileEnquiry();
    });

    if ($('.main-gallery-container').length > 0) {
        main_gallery_flkty = $('.main-gallery-container .gallery').flickity({
            cellAlign: 'left',
            contain: true,
            lazyLoad: 1,
            pageDots: false,
            prevNextButtons: true,
            wrapAround: false,
            setGallerySize: false,
            dragThreshold: 1,
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

        main_gallery_thumbs_flkty = $('.main-gallery-thumbs-wrapper .thumbs').flickity({
            asNavFor: '.gallery',
            contain: true,
            pageDots: false,
            prevNextButtons: false,
            draggable: false,
        });

        $('article.pdp .gallery .slide').click(function(){
            var thumb_id = $(this).attr('data-id');
            openFullscreenGallery(thumb_id);
        });

        $('.fullscreen-gallery .gallery-header .close').click(function(){
            closeFullscreenGallery();
        });
    }

    var selectedLocation = false;
    var locationSelectElement = $('.sidebar enquiry-box-component form select[name="locations"]');
    var locationSelectElementMobile = $('mobile-enquiry-component enquiry-box-component form select[name="locations"]');

    if (locationSelectElement.length > 0) {
        selectedLocation = locationSelectElement.val();
    }

    $('form select[name="locations"]').on('change', function(){
        selectedLocation = $(this).val();
    });

    var pdpLocationsStorage = localStorage.getItem('serp2pdp_selected_location');
    if (pdpLocationsStorage !== null) {
        pdpLocationsStorage = JSON.parse(pdpLocationsStorage);
        var itemId = $('#item_id').text();
        pdpLocationsStorage.reverse().some(function (storedSelectedLocation) {
            if (storedSelectedLocation.item_id == itemId) {
                let preselectedElementVal = undefined;

                locationSelectElement.find('option').each(function(index, option) {
                    if (
                        option.hasAttribute('data-location_ide1') &&
                        option.getAttribute('data-location_ide1') == storedSelectedLocation.location_ide1) {
                        preselectedElementVal = option.value;

                        return false;
                    }
                });

                if (preselectedElementVal) {
                    locationSelectElement.val(preselectedElementVal).change();
                    locationSelectElementMobile.val(preselectedElementVal).change();
                }

                return true;
            }
        });
    }

    // Scroll to reviews
    $('.click-to-scroll-reviews .seller-rating-badge').click(function(){
        $('html, body').animate({
            scrollTop: $('.reviews-heading').offset().top - 30
        }, 300);
    });

    // Show all reviews
    $('.show-all-reviews-btn').click(function(){
        $('.seller-reviews').addClass('expanded');
        $(this).hide();
    });
    
    // Show Sellers phone number(s)
    $('.show-seller-phone-btn').click(function(){
        $(this).parent().addClass('revealed');
        trackBackendPdpClick('pdp_phone_btn', 'desktop');
    });

    let topOffsetPhoneDetailsPopup = 0;
    let sellerCallTrackingMobileFlag = false;
    $('.floating-bottom-bar .call-seller-btn-mobile').click(function(){
        if (sellerCallTrackingMobileFlag === false) {
            trackBackendPdpClick('pdp_phone_btn', 'mobile');
            sellerCallTrackingMobileFlag = true;
            $('mobile-contact-details-popup').appendTo('.fullscreen-modal');
        }
        $('.fullscreen-modal').addClass('visible-popup');
        topOffsetPhoneDetailsPopup = $(document).scrollTop();
        headerModule.lockBodyScroll();

        $('html, body').animate({
            scrollTop: topOffsetPhoneDetailsPopup
        }, 0);
    });

    $('mobile-contact-details-popup .close-popup, mobile-contact-details-popup .modal-overlay').click(function(){
        $('.fullscreen-modal').removeClass('visible-popup');
        headerModule.unlockBodyScroll();

        $('html, body').animate({
            scrollTop: topOffsetPhoneDetailsPopup
        }, 0);
    });

    // Open textarea for message to seller
    $('.open-seller-message').click(function(){
        $(this).hide();
        $('.seller-message').removeClass('hidden');
    });

    $(document).on('change','mobile-enquiry-component .date-hour-picker input', function(){
        if ($(this).val() == '' || $(this).val() == null) {
            if ($(this).attr('type') == 'date') {
                var now = new Date();
                var day = ("0" + now.getDate()).slice(-2);
                var month = ("0" + (now.getMonth() + 1)).slice(-2);
                var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
                $(this).val(today); // HARDCODED - if on android user deletes date give him today's date
            } else if ($(this).attr('type') == 'time') {
                $(this).val('10:00'); // HARDCODED - if on android user deletes time give him 10:00 which is set by default
            }
        }

        updateDateOrTimeLabelValue($(this));
    });

    $('.dh-item-value.hour').click(function(){
        openCustomDropdown($(this));
    });

    $('.location-additional-info').click(function(){
        $('html, body').animate({
            scrollTop: $('.location-section').offset().top
        }, 300);
    });

    // Form submission
    $('.send-enquiry-btn').click(function(){
        formSubmit('desktop');
    });

    $('.submit-mobile-enquiry').click(function(){
        formSubmit('mobile');
    });
}
