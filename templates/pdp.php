<?php
    $itemData = apply_filters( 'getPDPdata', false );
    
    $visible = false;
    if (property_exists($itemData, 'visible')) $visible = $itemData->visible;

    if(!is_object($itemData) || empty($itemData) || !$visible){
        return fire404();
    }

    $svgs               = get_svgs();
    $description        = false;
    $hiring_terms       = false;
    $opening_hours      = false;
    $location           = false;
    $locations          = false;
    $seller_info        = false;
    $phone_numbers      = false;
    $name               = false;
    $images             = false;
    $seller_id          = false;
    $seller_rating      = false;
    $show_ratings       = false;
    $price              = fetchPrice($itemData);
    $breadcrumbs_data   = false;

    if (SPINOFFID === 'sportauto') {
        $breadcrumbs_data = getPdpBreadcrumbsData();
    } else {
        $breadcrumbs_data = getPdpBreadcrumbsData($itemData);
    }
    $last_link = end($breadcrumbs_data);

    if (property_exists($itemData, 'description')) $description = $itemData->description;
    if (property_exists($itemData, 'hiringTerms')) $hiring_terms = $itemData->hiringTerms;
    if (property_exists($itemData, 'locations')) $locations = $itemData->locations;
    if (property_exists($itemData, 'title')) $name = $itemData->title;
    if (property_exists($itemData, 'images')) $images = $itemData->images;
    if (property_exists($itemData, 'sellerRating')) {
        $seller_rating = $itemData->sellerRating;
        if (is_object($seller_rating) && property_exists($seller_rating, 'count') && $seller_rating->count > 0) $show_ratings = true;
    }

    if (property_exists($itemData, 'seller')) {
        $seller_info = $itemData->seller;
        $phone_numbers = getSellerPhoneNumbers($seller_info);
        $seller_id = $seller_info->id;
    }

    if (property_exists($itemData, 'location')) {
        $location = $itemData->location;
        $locations_count = $itemData->locationsCount;
        if (property_exists($location, 'openingHours')) $opening_hours = $location->openingHours;
        $locationAddress = generatePickupAddress($location);
    }

    $image_presets = [
        'fullscreen_gallery' => [
            'srcset' => [
                ['width=350&height=400&fit=bounds', '350w'],
                ['width=450&height=400&fit=bounds', '450w'],
                ['width=550&height=400&fit=bounds', '550w'],
                ['width=700&height=500&fit=bounds', '700w'],
                ['width=900&height=600&fit=bounds', '900w']
            ],
            'sizes' => '(max-width: 991px) 100vw, (min-width: 992px) and (max-width: 1199px) 700px, (min-width: 1200px) 900px'
        ],
        'main_gallery' => [
            'srcset' => [
                ['width=320&height=224&fit=crop', '320w'],
                ['width=375&height=263&fit=crop', '375w'],
                ['width=400&height=280&fit=crop', '400w'],
                ['width=450&height=315&fit=crop', '450w'],
                ['width=560&height=340&fit=crop', '560w'],
                ['width=750&height=420&fit=crop', '750w'],
                ['width=991&height=694&fit=crop', '991w']
            ],
            'sizes' => '(max-width: 991px) 100vw, (min-width: 992px) and (max-width: 1199px) 560px, (min-width: 1200px) 750px'
        ],
        'thumbnail' => [
            'srcset' => [
                ['width=79&height=56&fit=crop', '1x'],
                ['width=79&height=56&fit=crop&dpr=2', '2x']
            ]
        ]
    ];

    $pdpFlickityGalleries = buildPdpFlickityGallery($images, $image_presets, $name);

    // pre_dump($itemData);

    // SEO - Start
    $domain_name = getDomainName();
    $seo_title = $name . ' - ' . $domain_name;
    $seo_desc = $name . ' - ' . _t('SEO_DESC_DOMAIN', true) . ' ' .  $domain_name;

    $seo_img = false;
    if (isset($images) && !empty($images)) {
        $seo_img = getStaticSrc($images[0]->src);
    }

    buildMetaData($seo_title, $seo_desc, $seo_img);
    buildRobots('index,follow');
    // SEO - End
?>

<?php get_header(); ?>

    <article class="pdp" itemtype="https://schema.org/Product" itemscope>
        <?=getPriceForMicrodata($price);?>

        <?php if (SPINOFFID === 'sportauto'): ?>
            <span itemprop="brand" itemtype="https://schema.org/Brand" itemscope>
                <meta itemprop="name" content="<?=$last_link['name']?>" />
            </span>
        <?php endif ?>

        <div class="fullscreen-gallery">
            <div class="gallery-header">
                <div class="counter">
                    <span class="current-slide"></span> / <span class="all-slides"></span>
                </div>
                <div class="close"></div>
            </div>

            <div class="fullscreen-gallery-slides-wrapper">
                <div class="fullscreen-gallery-slides">
                    <?=$pdpFlickityGalleries['fullscreen_gallery_html']?>
                </div>
            </div>
        </div>

        <?php
            if ($phone_numbers) {
                get_shared_template_part('components/mobile-contact-details-popup', null, array(
                    'phone_numbers'  => $phone_numbers
                ));
            }

            get_shared_template_part('components/mobile-enquiry', null, array(
                'name'          => $name,
                'images'        => $images,
                'price'         => $price,
                'location'      => $locationAddress,
                'locations'     => $locations,
                'seller_id'     => $seller_id,
                'seller_phone'  => $phone_numbers,
                'last_link'     => $last_link,
            ));
        ?>

        <div class="content-sidebar-wrapper">
            <div class="container content-sidebar-wrapper-container">
                <div class="content">
                    <div class="main-gallery-container">
                        <?php if ($show_ratings): ?>
                            <div class="seller-rating-mobile-badge click-to-scroll-reviews">
                                <?=getSellerRatingBadgeHtml($seller_rating)?>
                            </div>
                        <?php endif ?>
                        <div class="gallery">
                            <?=$pdpFlickityGalleries['main_gallery_html']?>
                        </div>
                    </div>

                    <?php
                        $thumbs_html = $pdpFlickityGalleries['thumbs_html'];
                        if ($thumbs_html) {
                            echo '<div class="main-gallery-thumbs-wrapper">';
                                echo '<div class="thumbs-inner">';
                                    echo '<div class="thumbs">';
                                        echo $thumbs_html;
                                    echo '</div>';
                                echo '</div>';
                            echo '</div>';
                        }
                    ?>

                    <div class="links-row">
                        <?php
                            if (key_exists('link', $last_link)) {
                                echo '<div class="more-results"><a href="' . $last_link['link'] . '">< ' . _t('MORE_ITEMS', true) . '</a></div>';
                            }
                        ?>
                        <div class="article-number">
                            <?=_t('Article') . ' #'?>
                            <span id="item_id" itemprop="productID"><?=$itemData->articleId?></span>
                        </div>
                        <?=generateBreadcrumbsHtml($breadcrumbs_data, true);?>
                    </div>

                    <h1 itemprop="name"><?=$name?></h1>

                    <?php if ($location) { ?>
                        <div class="location">
                            <div class="icon"><?=$svgs['location_pin']?></div>
                            <?php
                                if (SPINOFFID === 'sportauto' || SPINOFFID === 'oldtimer') {
                                    echo generateCompanyAddress($location, false, true);
                                } else {
                                    echo generateCompanyAddress($location, false, $itemData);
                                }
                            ?>
                            <?php
                                if ($locations_count && $locations_count > 1) {
                                    echo '<div class="location-additional-info">(<u>+ ' . ($locations_count - 1) . ' ';
                                    $locations_count==2 ? _t('other location') : _t('other locations');
                                    echo '</u>)</div>';
                                }
                            ?>
                        </div>
                    <?php } ?>

                    <div class="mobile-price-container">
                        <?=getPriceHtml($price)?>
                    </div>

                    <div class="details-content">
                        <div class="rating-last-booked-container click-to-scroll-reviews">
                            <?=getSellerRatingBadgeHtml($seller_rating)?>
                            <div class="last-booked-tag">
                                <div class="icon"><?=$svgs['success']?></div>
                                <span><?=_t('Last enquiry:')?> <?=getLastEnquiryHours($itemData->articleId);?> <?=_t('hours')?></span>
                            </div>
                        </div>
                        <?php if (!empty($itemData->properties)): ?>
                            <?php
                                if (SPINOFFID === 'sportauto') {
                                    echo '<div class="top-properties double-column">';
                                        foreach ($itemData->selected_properties as $key => $property) {
                                            $property_label = false;
    
                                            switch ($key) {
                                                case 'power':
                                                    $property_label = _t('Power', true);
                                                    break;
    
                                                case 'year_built':
                                                    $property_label = _t('Year built', true);
                                                    break;
    
                                                case 'transmission':
                                                    $property_label = _t('Transmission', true);
                                                    break;
    
                                                case 'age_limit':
                                                    $property_label = _t('Minimum age', true);
                                                    break;
    
                                                case 'deposit':
                                                    $property_label = _t('Deposit ammount', true);
                                                    break;
    
                                                case 'credit_card':
                                                    $property_label = _t('Credit card', true);
                                                    break;
                                            }
    
                                            if ($property_label && $property != '') {
                                                echo '<div class="property">';
                                                    echo '<label>' . $property_label . '</label>';
                                                    echo '<div class="value">' . $property . '</div>';
                                                echo '</div>';
                                            }
                                        }
                                    echo '</div>';
                                } else {
                                    echo '<div class="top-properties">';
                                        foreach ($itemData->properties as $key => $property) {
                                            echo '<div class="property">';
                                                echo '<label>' . $property->name . '</label>';
                                                echo '<div class="value">' . $property->value . '</div>';
                                            echo '</div>';
                                        }
                                    echo '</div>';
                                }
                            ?>
                        <?php endif; ?>

                        <?php if ($description) { ?>
                            <?php echo '<h2 class="description">' . _t('Description', true) . '</h2>'; ?>
                            <meta itemprop="description" content="<?=strip_tags($description)?>" />
                            <?php
                                get_shared_template_part('components/read-more-text-container', null, array(
                                    'component_class'   => 'description',
                                    'text'              => $description,
                                ));
                            ?>
                        <?php } ?>

                        <?php if ($hiring_terms) { ?>
                            <hr>
                            <h2><?=_t('Hiring terms')?></h2>
                            <?php
                                get_shared_template_part('components/read-more-text-container', null, array(
                                    'component_class'   => 'hiring-terms',
                                    'text'              => $hiring_terms,
                                ));
                            ?>
                        <?php } ?>

                        <?php if ($show_ratings) { ?>
                            <hr>
                            <div class="reviews-heading">
                                <h2><?=_t('Reviews')?></h2>
                                <?=getSellerRatingBadgeHtml($seller_rating, 'large', true)?>
                            </div>
                            <div class="seller-reviews">
                                <div class="reviews">
                                    <?php
                                        $sellerReviewsObj = fetchSellerReviews($seller_id);
                                        if (
                                            $sellerReviewsObj &&
                                            property_exists($sellerReviewsObj, 'results')
                                        ) {
                                            foreach ($sellerReviewsObj->results as $key => $review): ?>
                                                <div class="review" itemprop="review" itemtype="https://schema.org/Review" itemscope>
                                                    <div class="name-rating">
                                                        <div class="name" itemprop="author" itemtype="https://schema.org/Person" itemscope>
                                                            <span itemprop="name"><?php if ($review->name === null) echo _t('Renter'); else echo $review->name;?></span>
                                                        </div>
                                                        <div class="stars-wrapper" itemprop="reviewRating" itemtype="https://schema.org/Rating" itemscope>
                                                            <meta itemprop="ratingValue" content="<?=$review->rating?>" />
                                                            <meta itemprop="bestRating" content="5" />
                                                            <div class="stars-icon stars-base"><?=$svgs['stars']?></div>
                                                            <div class="stars-icon stars-yellow" style="width: <?=$review->rating * 20?>%;"><?=$svgs['stars']?></div>
                                                        </div>
                                                    </div>
                                                    <div class="date"><?=date('d.m.Y', strtotime($review->createdAt));?></div>
                                                    <div class="comment"><?=esc_html($review->comment)?></div>
                                                    <?php if ($review->reply): ?>
                                                        <div class="reply">
                                                            <div class="reply-name"><?=_t('Suppliers response')?> - <?=date('d.m.Y', strtotime($review->repliedAt));?></div>
                                                            <div class="reply-content"><?=esc_html($review->reply)?></div>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                        <?php endforeach; ?>
                                    <?php } ?>
                                </div>
                                <?php if (
                                    $sellerReviewsObj &&
                                    property_exists($sellerReviewsObj, 'total') &&
                                    $sellerReviewsObj->total > 3):
                                ?>
                                    <div class="show-all-reviews-btn btn __bold __shadow __outlined"><?=_t('Show all reviews')?></div>
                                <?php endif; ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <div class="sidebar">
                    <div class="enquiry-box">
                        <div class="price-container">
                            <?=getPriceHtml($price)?>
                        </div>
                        <div class="title hide-after-enq-submit"><?=_t('Enquire on this item')?></div>
                        <?php
                            get_shared_template_part('components/enquiry-box', null, array(
                                'item_name'     => $name,
                                'price'         => $price,
                                'location'      => $locationAddress,
                                'locations'     => $locations,
                                'seller_id'     => $seller_id,
                                'seller_phone'  => $phone_numbers,
                                'last_link'     => $last_link,
                                'item_image'    => $pdpFlickityGalleries['email_img_src'],
                            ));
                        ?>
                    </div>

                    <?php
                        if ($seller_info) {
                            if (property_exists($seller_info, 'contact')) {
                                if (property_exists($seller_info->contact, 'phoneNumber') || property_exists($seller_info->contact, 'mobileNumber')) {
                                    echo '<div class="seller-info-wrapper">';
                                        get_shared_template_part('components/seller-info-box', null, array(
                                            'seller_info' => $seller_info,
                                        ));
                                    echo '</div>';
                                }
                            }
                        }
                    ?>
                </div>
            </div>

            <?php
                $category = false;
                if (SPINOFFID === 'limo' || SPINOFFID === 'zelte') {
                    $category = getCategorySlugFromApi($itemData);
                }

                get_shared_template_part(
                    'components/featured-items',
                    null,
                    array(
                        'card_type' => 'normal',
                        'location' => slugify($location->city),
                        'item_id' => $itemData->articleId,
                        'category' => $category,
                    )
                );
            ?>

            <div class="container">
                <div class="location-section">
                    <hr>
                    <div class="location-section-inner">
                        <div class="left-block">
                            <div class="location-info">
                                <h2><?=$locations_count && $locations_count > 1 ? _t('Article locations') : _t('Article location')?></h2>
                                <?php
                                    if ($locations && count($locations) > 1) {
                                        get_shared_template_part('components/read-more-text-container', null, array(
                                            'component_class'   => 'locations',
                                            'btn_text'          => _t('Show all...', true),
                                            'text'              => getAllLocationsList($locations),
                                        ));
                                    } else {
                                        if (SPINOFFID === 'sportauto') {
                                            echo generateCompanyAddress($location, true, false);
                                        } else {
                                            echo generateCompanyAddress($location, true);
                                        }
                                    }
                                ?>
                            </div>

                            <?php
                                if ($opening_hours) {
                                    echo '<div class="opening-hours">';
                                        echo '<h2>' . _t('Opening hours', true) . '</h2>';

                                        // Quick & dirty sort of days
                                        $days_of_week = [
                                            'mon' => _t('Monday', true),
                                            'tue' => _t('Tuesday', true),
                                            'wed' => _t('Wednesday', true),
                                            'thu' => _t('Thursday', true),
                                            'fri' => _t('Friday', true),
                                            'sat' => _t('Saturday', true),
                                            'sun' => _t('Sunday', true),
                                        ];

                                        foreach ($days_of_week as $key => $value) {
                                            if (property_exists($opening_hours, $key)) {
                                                if (property_exists($opening_hours->$key, 'raw')) {
                                                    echo '<div class="day-row">';
                                                        echo '<div class="day">' . $value . '</div>';
                                                        echo '<div class="hours">' . $opening_hours->$key->raw . '</div>';
                                                    echo '</div>';
                                                }
                                            }
                                        }
                                    echo '</div>';
                                }
                            ?>
                        </div>

                        <?php if (property_exists($location, 'geo')) { ?>
                            <div id="map">
                                <iframe
                                style="border:0"
                                title="<?=_t('Article location')?>"
                                loading="lazy"
                                allowfullscreen
                                src="https://www.google.com/maps/embed/v1/place?key=<?=getGMapsApiKey();?>
                                    &q=<?=$location->geo->lat?>,<?=$location->geo->lng?>">
                                </iframe>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="floating-bottom-bar">
            <div class="floating-bottom-bar-inner container">
                <?php
                    if ($phone_numbers) {
                        echo '<div class="btn call-seller-btn-mobile __dark __solid __shadow __bold"><div class="icon">' . $svgs['phone'] . '</div></div>';
                    }
                ?>
                <div class="btn open-mobile-enquiry __solid __color __shadow __bold"><?=_t('Enquire now')?></div>
            </div>
        </div>
    </article>

<?php get_footer(null, [ 'class' => 'PDP' ]);
