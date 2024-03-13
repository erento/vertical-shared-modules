<?php
    if ( ! defined( 'ABSPATH' ) ) exit;

    function getRequestUniqueId() {
        return getSpinoffOrigin() . '_' . time();
    }

    // Add prefix to "/static" URLs
    function getStaticSrc($src) {
        $src = str_replace('static/image-products/', 'img/', $src); // HARDCODED
        $modified_src = pathinfo($src, PATHINFO_DIRNAME) . '/' . pathinfo($src, PATHINFO_FILENAME) . '_original.' . pathinfo($src, PATHINFO_EXTENSION); // TEMPORARY HARDCODED

        return "https://www.eren.to" . $modified_src; // HARDCODED
    }

    function getCurrentUrl() {
        global $wp;
        return trailingslashit(home_url(add_query_arg(array(), $wp->request)));
    }

    function getHomeUrl() {
        return trailingslashit(home_url());
    }

    function getDomainName() {
        return $_SERVER['HTTP_HOST'];
    }

    function getPDPsuffix() {
        return "-pdp";
    }

    function getTaxonomyPermalink() {
        $base_url = home_url();
        $taxonomy = getMainTaxonomy()['slug'];

        return trailingslashit($base_url . '/' . $taxonomy);
    }

    function getPdpPermalink($articleId, $brandSlug = false) {
        if ($brandSlug) {
            return trailingslashit(getTaxonomyPermalink() . $brandSlug . '/' . $articleId . getPDPsuffix());
        } else {
            return trailingslashit(getTaxonomyPermalink() . $articleId . getPDPsuffix());
        }
    }

    function getBrandSerpPermalink($brandSlug) {
        return trailingslashit(getTaxonomyPermalink() . $brandSlug);
    }

    function getBrandLocationSerpPermalink($brandSlug, $locationSlug) {
        return trailingslashit(getTaxonomyPermalink() . $brandSlug . '/' . $locationSlug);
    }

    function getBrandModelLocationSerpPermalink($brandSlug, $modelSlug, $locationSlug) {
        return trailingslashit(getTaxonomyPermalink() . $brandSlug . '/' . $modelSlug . '/' . $locationSlug);
    }

    function getLocationSerpPermalink($locationSlug) {
        return trailingslashit(getTaxonomyPermalink() . $locationSlug);
    }

    function getMietenCategoryPermalink($mieten_category_slug) {
        return trailingslashit(getTaxonomyPermalink() . $mieten_category_slug);
    }

    function getMietenCategoryLocationSerpPermalink($mieten_category_slug, $locationSlug) {
        return trailingslashit(getTaxonomyPermalink() . $mieten_category_slug . '/' . $locationSlug);
    }

    function getLocationSlugFromName($locationName) {
        return strtolower($locationName);
    }

    function getModelSerpPermalink($brandSlug, $modelSlug) {
        return trailingslashit(getTaxonomyPermalink() . $brandSlug . '/' . $modelSlug);
    }

    function getSrcsetString($srcsets, $imageSrc) {
        $i = 0;
        $srcset_string = '';
        foreach ($srcsets as $key => $srcset) {
            $i++;
            $srcset_string .= $imageSrc . '?' . $srcset[0] . ' ' . $srcset[1];
            if (count($srcsets) == $i) break;
            $srcset_string .= ', ';
        }

        return $srcset_string;
    }

    function getSmallestSrc($srcsets, $imageSrc) {
        return $imageSrc . '?' . $srcsets[0][0];
    }

    function getEmailImgSrc($imageSrc) {
        return $imageSrc . '?width=360&height=200&fit=crop';
    }

    function slugify($title) {
        $title = str_replace('Ä', 'ae', $title);
        $title = str_replace('ä', 'ae', $title);
        $title = str_replace('Ö', 'oe', $title);
        $title = str_replace('ö', 'oe', $title);
        $title = str_replace('Ü', 'ue', $title);
        $title = str_replace('ü', 'ue', $title);
        $title = str_replace('ẞ', 'ss', $title);
        $title = str_replace('ß', 'ss', $title);

        return sanitize_title($title);
    }

    function getHtmlSitemapUrl() {
        return 'https://www.erento.com/api/search/staedte/mieten/fahrzeuge_fluggeraete/special_cars/oldtimer?origin=' . getSpinoffOrigin() . '&_requestUniqueId=' . getRequestUniqueId() . '&countryISO=com&lang=de'; // HARDCODED
    }

    function getHtmlSitemapUrls() {
        $mieten_categories = apply_filters('getMietenCategories', false);
        $sitemap_urls_array = [];

        foreach ($mieten_categories as $category_slug => $category_array) {
            $sitemap_urls_array[$category_slug]['slug'] = $category_slug;
            $sitemap_urls_array[$category_slug]['title'] = $category_array['h1_title'] . ' in ';
            $sitemap_urls_array[$category_slug]['api_url'] = 'https://www.erento.com/api/search/staedte' . $category_array['erento_api_call'] . '?origin=' . getSpinoffOrigin() . '&_requestUniqueId=' . getRequestUniqueId() . '&countryISO=com&lang=de';
        }

        return $sitemap_urls_array;
    }
    
    function fetchHtmlSitemap() {
        if (SPINOFFID === 'oldtimer') {
            $url = getHtmlSitemapUrl();
            $sitemap_fetch = @file_get_contents($url);
            if (!$sitemap_fetch) return false;
            $sitemap_fetch = json_decode($sitemap_fetch);
    
            return $sitemap_fetch->cities;
        } else {
            $sitemap_array = [];
            $urls = getHtmlSitemapUrls();
    
            foreach ($urls as $key => $url_array) {
                $sitemap_fetch = @file_get_contents($url_array['api_url']);
                if ($sitemap_fetch) {
                    $sitemap_fetch = json_decode($sitemap_fetch);
    
                    foreach ($sitemap_fetch->cities as $character => $cities_array) {
                        if (empty($sitemap_array[$character])) $sitemap_array[$character] = [];
    
                        foreach ($cities_array as $key => $city_name) {
                            $city_slug = slugify($city_name);
                            $city_url = getMietenCategoryLocationSerpPermalink($url_array['slug'], $city_slug);
                            $city_url = '<a href="' . $city_url . '" target="_blank">' . $url_array['title'] . $city_name . '</a>';
    
                            array_push($sitemap_array[$character], $city_url);
                        }
                    }
                }
            }
    
            return $sitemap_array;
        }
    }

    function getItemUrl($articleId) {
        $url = 'https://www.erento.com/api/search/products/'.$articleId.'?origin=' . getSpinoffOrigin() . '&_requestUniqueId=' . getRequestUniqueId() . '&forFrontend=true&lang=de';

        return $url;
    }

    function fetchPdpData($articleId) {
        $url = getItemUrl($articleId);
        $selected_product_data = false;

        $product_fetch = @file_get_contents($url);
        if ($product_fetch) {
            $product_fetch = json_decode($product_fetch);

            if ($product_fetch->ctx->total != 0) {
                $product_fetch_results = $product_fetch->results[0];

                if ($product_fetch_results->articleId == $articleId) {
                    $selected_product_data = $product_fetch_results;
                }
            }
        }

        return $selected_product_data;
    }

    function getSellerPhoneNumbers($sellerInfo) {
        $phone_numbers = [];

        if (is_object($sellerInfo)) {
            if (property_exists($sellerInfo, 'contact') && property_exists($sellerInfo->contact, 'phoneNumber')) {
                array_push($phone_numbers, $sellerInfo->contact->phoneNumber);
            }

            if (property_exists($sellerInfo, 'contact') && property_exists($sellerInfo->contact, 'mobileNumber')) {
                array_push($phone_numbers, $sellerInfo->contact->mobileNumber);
            }
        }

        if (empty($phone_numbers)) return false;
        else return $phone_numbers;
    }

    function generateSellerUserUrl($userId) {
        return 'https://www.erento.com/api/user/' . $userId . '?_requestUniqueId=' . getRequestUniqueId();
    }

    function getSellerEmail($sellerId) {
        $url = generateSellerUserUrl($sellerId);
        $fetched_data = @file_get_contents($url);

        $seller_email = false;

        if ($fetched_data) {
            $fetched_data = json_decode($fetched_data);

            if (property_exists($fetched_data, 'notificationEmail') && !empty($fetched_data->notificationEmail)) {
                $seller_email = $fetched_data->notificationEmail;
            } else if (property_exists($fetched_data, 'userName') && !empty($fetched_data->userName)) {
                $seller_email = $fetched_data->userName;
            }
        }

        return $seller_email;
    }

    function generatePickupAddress($location) {
        $html = '';

            if (property_exists($location, 'postalCode') || property_exists($location, 'city')) {
                if (property_exists($location, 'postalCode')) $html .= $location->postalCode;
                if (property_exists($location, 'postalCode') && property_exists($location, 'city')) $html .= ' ';
                if (property_exists($location, 'city')) $html .= $location->city;
            }

            if (property_exists($location, 'countryISO')) {
                $html .= ', ' . $location->countryISO;
            }

            if ($location->isPrimary) {
                $html .= ' (' . _t('Article location', true) . ')';
            } elseif ($location->isDelivery) {
                if ($location->locationOptionsType == 'delivery') $html .= ' (' . _t('Delivery', true) . ')';
                if ($location->locationOptionsType == 'shipping') $html .= ' (' . _t('Shipping', true) . ')';
            }

        return $html;
    }

    function getAllLocationsList($locations) {
        $html = '';
        foreach ($locations as $key => $locationObj) {
            $locationString = generatePickupAddress($locationObj);
            $html .= '<div>' . $locationString . '</div>';
        }

        return $html;
    }

    function generateSellerCompanyInfo($sellerInfo) {
        // Company name
        $company_name = false;
        if (property_exists($sellerInfo, 'name') && $sellerInfo->name != '') {
            $company_name = $sellerInfo->name;
        } else {
            if (property_exists($sellerInfo, 'contact') && property_exists($sellerInfo->contact, 'company')) {
                $obj_company_name = $sellerInfo->contact->company;
                if ($obj_company_name != '') $company_name = $obj_company_name;
            }
        }
        if ($company_name) echo '<div class="seller-name">' . $company_name . '</div>';

        // Company address
        if (property_exists($sellerInfo, 'contact')) {
            echo '<div class="seller-address">';
                echo generateCompanyAddress($sellerInfo->contact, true);
            echo '</div>';
        }
    }

    function generateCompanyAddress($location, $includeStreet = false, $includeLinkToSerp = false) {
        $html = '<span class="company-address-wrapper">';

            if ($includeStreet && property_exists($location, 'street')) {
                $html .= '<span>' . $location->street . '</span>';
            }

            if (property_exists($location, 'postalCode') || property_exists($location, 'city')) {
                $html .= '<span>';
                    if (property_exists($location, 'postalCode')) $html .= $location->postalCode;
                    if (property_exists($location, 'postalCode') && property_exists($location, 'city')) $html .= ' ';
                    if (property_exists($location, 'city')) {
                        if ($includeLinkToSerp !== false) {
                            $city_slug = slugify($location->city);
                            if (SPINOFFID === 'sportauto' || SPINOFFID === 'oldtimer') {
                                $html .= '<a href="' . getLocationSerpPermalink($city_slug) . '">';
                            } else {
                                $mieten_category_slug = getCategorySlugFromApi($includeLinkToSerp);
                                $html .= '<a href="' . getMietenCategoryLocationSerpPermalink($mieten_category_slug, $city_slug) . '">';
                            }
                        }
                        $html .= $location->city;
                        if ($includeLinkToSerp !== false) $html .= '</a>';
                    }
                $html .= '</span>';
            }

            if (property_exists($location, 'countryISO')) {
                $html .= '<span>' . countryIsoToCountry($location->countryISO) . '</span>';
            }

        $html .= '</span>';

        return $html;
    }

    function buildPdpFlickityHtml($key, $image_src, $selected_image_presets, $name, $conter, $allLazyLoded = false) {
        $smallest_src = getSmallestSrc($selected_image_presets['srcset'], $image_src);
        $srcset_string = getSrcsetString($selected_image_presets['srcset'], $image_src);

        $html = '<div class="slide" data-id="' . $key . '">';
            $html .= '<img ';
                if ($conter == 0 && $allLazyLoded === false) {
                    $html .= 'itemprop="image" ';
                    $html .= 'src="' . $smallest_src . '" ';
                    $html .= 'srcset="' . $srcset_string . '" ';
                } else {
                    // $html .= 'loading="lazy" ';
                    $html .= 'data-flickity-lazyload="' . $smallest_src . '" ';
                    $html .= 'data-flickity-lazyload-srcset="' . $srcset_string . '" ';
                }
                $html .= 'sizes="' . $selected_image_presets['sizes'] . '" ';
                $html .= 'alt="' . $name . '"';
            $html .= '>';
        $html .= '</div>';

        return $html;
    }

    function buildPdpFlickityGallery($images, $image_presets, $name) {
        $returnObject = [
            'main_gallery_html' => '<div class="no-photo-slide">' . _t('No photo', true) . '</div>',
            'fullscreen_gallery_html' => '<div class="no-photo-slide">' . _t('No photo', true) . '</div>',
            'thumbs_html' => false,
            'email_img_src' => false
        ];

        if ($images) {
            $htmlMainGallery = '';
            $htmlFullscreenGallery = '';
            $htmlThumbs = '';
            $conter = 0;

            foreach ($images as $key => $image) {
                $image_src = getStaticSrc($image->src);
                if ($conter == 0) $returnObject['email_img_src'] = getEmailImgSrc($image_src);

                // build main & fullscreen gallery
                $htmlMainGallery .= buildPdpFlickityHtml($key, $image_src, $image_presets['main_gallery'], $name, $conter, false);
                $htmlFullscreenGallery .= buildPdpFlickityHtml($key, $image_src, $image_presets['fullscreen_gallery'], $name, $conter, true);

                // build thumbs
                $thumb_src = getSmallestSrc($image_presets['thumbnail']['srcset'], $image_src);
                $thumb_srcset = getSrcsetString($image_presets['thumbnail']['srcset'], $image_src);
                $htmlThumbs .= '<div class="thumb" >';
                    $htmlThumbs .= '<img loading="lazy" width="79" height="56" src="' . $thumb_src . '" srcset="' . $thumb_srcset . '" alt="' . $name . '">';
                $htmlThumbs .= '</div>';
                $conter++;
            }

            $returnObject['main_gallery_html'] = $htmlMainGallery;
            $returnObject['fullscreen_gallery_html'] = $htmlFullscreenGallery;
            $returnObject['thumbs_html'] = $htmlThumbs;
        }

        return $returnObject;
    }

    function generateBreadcrumbsHtml($breadcrumbsData, $isLastItemLink = false) {
        $breadcrumbs_html = '<ol class="breadcrumbs" itemscope itemtype="https://schema.org/BreadcrumbList">';
        for ($i=0; $i < sizeof($breadcrumbsData); $i++) {
            if (!empty($breadcrumbsData[$i]['name'])) {
                $breadcrumbs_html .= '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
                    $breadcrumbs_li_content = '<span itemprop="name">'.$breadcrumbsData[$i]['name'].'</span>';

                    if (($i < sizeof($breadcrumbsData) - 1) || $isLastItemLink) {
                        $breadcrumbs_li_content = '<a itemprop="item" href="'.$breadcrumbsData[$i]['link'].'">' . $breadcrumbs_li_content . '</a>';
                    }

                    $breadcrumbs_html .= $breadcrumbs_li_content;
                    $breadcrumbs_html .= '<meta itemprop="position" content="' . ($i + 1) . '" />';
                $breadcrumbs_html .= '</li>';
            }
        }
        $breadcrumbs_html .= '</ol>';

        return $breadcrumbs_html;
    }

    function getPagination($data) {
        $svgs           = get_svgs();
        $total_count    = $data->total;
        $from           = $data->from;
        $size           = $data->size;
        $pages          = ceil($total_count/$size);
        $current_page   = $from/$size + 1;
        $current_count  = $size;
        $current_url    = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
        $current_from   = (($current_page - 1) * $size) + 1;

        if ($pages > 1) {
            $current_count = $current_page * $size;

            if ($current_page == $pages) {
                $current_count = $total_count;
            }
        } else {
            $current_count = $total_count;
        }

        $pagination_html = '<div class="serp-pagination">';

            $pagination_html .= '<div class="pagination">';
                for ($i=1; $i < $pages+1; $i++) {
                    if ($i == 1) {
                        $pagination_html .= '<div class="outer-btn previous';
                        if ($i == $current_page) $pagination_html .= ' disabled';
                        $pagination_html .= '">';
                        if ($i != $current_page) $pagination_html .= '<a href="' . $current_url . '?pag=' . ($current_page - 1) . '" class="linkOverlay"></a>';
                        $pagination_html .= '<div class="icon">' . $svgs['caret'] . '</div></div>';

                        $pagination_html .= '<div class="pages">';
                    }

                    if ($i == $current_page) {
                        $pagination_html .= '<div class="page active">' . $i . '</div>';
                    } else {
                        $pagination_html .= '<a href="' . $current_url . '?pag=' . $i . '" class="page">' . $i . '</a>';
                    }

                    if ($i == $pages) {
                        $pagination_html .= '</div>';

                        $pagination_html .= '<div class="outer-btn next';
                        if ($i == $current_page) $pagination_html .= ' disabled';
                        $pagination_html .= '">';
                        if ($i != $current_page) $pagination_html .= '<a href="' . $current_url . '?pag=' . ($current_page + 1) . '" class="linkOverlay"></a>';
                        $pagination_html .= '<div class="icon">' . $svgs['caret'] . '</div></div>';
                    }
                }
            $pagination_html .= '</div>';

            $pagination_html .= '<div class="count">Zeige ' . $current_from . ' - ' . $current_count . ' von ' . $total_count . ' Ergebnissen</div>';
        $pagination_html .= '</div>';

        return $pagination_html;
    }

    function fetchFeaturedItems() {
        $from = 0;
        $size = 30;
        $url = generateSerpUrl($from, $size);

        return fetchAndResolveItems($url);
    }

    // Only for SPORTAUTO spinoff
    function resolveSerpLocation($ctx) {
        $location_name = resolveSerpLocationFromApi($ctx);
        
        if ($location_name === false) {
            apply_filters('removeLocationForTaxonomyPath', false);
        } else {
            apply_filters('setLocationNameForTaxonomyPath', $location_name);
        }

        return $location_name;
    }

    function resolveSerpLocationFromApi($ctx) {
        if (is_object($ctx) && property_exists($ctx, 'geocodedAddress')) {
            $address            = json_decode($ctx->geocodedAddress, true);
            $address_component  = $address['results'][0]['address_components'];
            $formatted_address  = $address['results'][0]['formatted_address'];
            apply_filters('setFormattedAddress', $formatted_address);

            foreach ($address_component as $key => $value) {
                
                if (array_key_exists('types', $value)){
                    $types = $value['types'];

                    if (in_array('locality', $types) || in_array('postal_town', $types)) {
                        return $value['long_name'];
                    }
                }
            }

            $splitFormattedAddress = explode(',', $formatted_address);
            if (count($splitFormattedAddress) === 2) {
                return $splitFormattedAddress[0];
            } else {
                foreach ($address_component as $key => $value) {
                    if (array_key_exists('types', $value)){
                        $types = $value['types'];

                        if (in_array('country', $types)) {
                            return $value['long_name'];
                        }
                    }
                }
            }

            return false;
        } else {
            return false;
        }
    }

    function formatIndexableLocations($indexableLocationsString) {
        $indexable_locations_array = preg_split('/<br[^>]*>/i', $indexableLocationsString);
        $indexable_locations_array = array_map('ltrim', $indexable_locations_array);
        $indexable_locations_array = array_filter($indexable_locations_array); // clean up empty value rows

        return $indexable_locations_array;
    }

    function getLocationHtml($locationName, $locationsCount) {
        $location_html = '';

        if ($locationName != '') {
            $location_html .= '<div class="location-with-pin">';
                $location_html .= '<div class="icon">' . get_svgs()['location_pin'] . '</div>';
                $location_html .= '<span>' . $locationName;
                    if ($locationsCount > 1) {
                        $location_html .= ' + ' . ($locationsCount - 1) . ' ';
                        if ($locationsCount == 2) $location_html .= _t('other location', true);
                        else $location_html .= _t('other locations', true);
                    }
                $location_html .= '</span>';
            $location_html .= '</div>';
        }

        return $location_html;
    }

    // only for SPORTAUTO spinoff
    function isLocationSerp($taxonomyParams) {
        if (is_array($taxonomyParams) &&
            (count($taxonomyParams) == 1) &&
            array_key_exists('location', $taxonomyParams)) {
            return true;
        } else return false;
    }

    function getLastEnquiryHours($articleId) {
        $currentHour = (int)date('H');
        $articleLastNum = (int)$articleId[-1];
        $lastEnqHour = $currentHour + $articleLastNum;

        if ($lastEnqHour > 23) {
            $lastEnqHour = $lastEnqHour - 23;
        } else if ($lastEnqHour == 0) {
            $lastEnqHour = $lastEnqHour + 1;
        }

        return $lastEnqHour;
    }

    function getSellerRatingBadgeHtml($sellerRating, $size = 'small', $schema = false) {
        if (!is_object($sellerRating) || !property_exists($sellerRating, 'count') || $sellerRating->count === 0) return false;

        $badgeHtml = '<div class="seller-rating-badge';
        if ($size === 'large') $badgeHtml .= ' large';
        $badgeHtml .= '"';
        if ($schema) $badgeHtml .= 'itemprop="aggregateRating" itemtype="https://schema.org/AggregateRating" itemscope';
        $badgeHtml .= '>';
        $badgeHtml .= '<div class="icon">' . get_svgs()['star'] . '</div>';
        $badgeHtml .= '<span class="rating"';
        if ($schema)  $badgeHtml .= ' itemprop="ratingValue"';
        $badgeHtml .= '>' . number_format($sellerRating->rating, 1) . '</span>';
        $badgeHtml .= '<span class="count">(<span';
        if ($schema)  $badgeHtml .= ' itemprop="reviewCount"';
        $badgeHtml .= '>' . $sellerRating->count . '</span>)</span>';
        $badgeHtml .= '</div>';

        return $badgeHtml;
    }

    function fetchSellerReviews($sellerId) {
        $url = 'https://www.erento.com/api/reviews/reviews/seller/' . $sellerId . '?page=0&size=30&_requestUniqueId=' . getRequestUniqueId();

        $reviews_fetch = @file_get_contents($url);
        if (!$reviews_fetch) return false;

        return json_decode($reviews_fetch);
    }

    function getPriceHtml($priceArray) {
        $price_html = '<div class="price-wrapper">';

        if ( is_object($priceArray) && property_exists($priceArray, 'price')) {
            $price_array    = $priceArray->price;
            $base_price     = $price_array->basePrice / 100;
            $price_unit     = $price_array->durationLabel;
            $currency       = $price_array->currency;
            $type           = $price_array->type;

            $price = number_format((float)$base_price, 2, ',', '') . ' €';

            if ($type == 'on_request') {
                $price_html .= '<span class="on-request">' . _t('Price on request', true) . '</span>';
            } else {
                $price_html .= '<span class="from">' . _t('from', true) . '</span>';
                $price_html .= '<span class="price">' . $price . '</span>';
                $price_html .= '<span class="unit">/ ' . $price_unit . '</span>';
            }
        } else {
            $price_html .= '<span class="error">' . _t('Price could not be verified', true) . '</span>';
        }

        $price_html .= '</div>';

        return $price_html;
    }

    function getPriceInText($priceArray) {
        $price_txt = '';

        if ( is_object($priceArray) && property_exists($priceArray, 'price')) {
            $price_array    = $priceArray->price;
            $base_price     = $price_array->basePrice / 100;
            $price_unit     = $price_array->durationLabel;
            $currency       = $price_array->currency;
            $type           = $price_array->type;

            $price = number_format((float)$base_price, 2, ',', '') . ' €';

            if ($type == 'on_request') {
                $price_txt .= _t('Price on request', true);
            } else {
                $price_txt .= _t('from', true) . ' ' . $price . '/ ' . $price_unit;
            }
        } else {
            $price_txt .= _t('Price could not be verified', true);
        }

        return $price_txt;
    }

    function getPriceForMicrodata($priceArray) {
        if ( is_object($priceArray) && property_exists($priceArray, 'price')) {
            $price_array    = $priceArray->price;
            $base_price     = $price_array->basePrice / 100;
            $currency       = $price_array->currency;
            $type           = $price_array->type;

            $price = number_format((float)$base_price, 2, '.', '');

            if ($type == 'on_request') return false;

            $microdata = '<span itemprop="offers" itemtype="https://schema.org/AggregateOffer" itemscope>';
                $microdata .= '<meta itemprop="priceCurrency" content="EUR" />';
                $microdata .= '<meta itemprop="price" content="' . $price . '" />';
            $microdata .= '</span>';

            return $microdata;
        } else return false;
    }

    // Get prices for Serp items
    function fetchPrice($item) {
        $item_number    = $item->ide1;
        $location       = $item->location->ide1;
        $product_id     = $item->id;
        $location_id    = $item->location->id;
        $article_id     = $item->articleId;
        $user_id        = $item->seller->id;
        $is_external    = $item->isExternal;
        $min_rent_per   = $item->minRentalPeriodHours;

        $url = 'https://www.erento.com/api/price/p?itemNumber=' . $item_number . '&location=' . $location . '&productId=' . $product_id . '&locationId=' . $location_id . '&articleId=' . $article_id . '&userId=' . $user_id . '&isExternal=' . $is_external . '&minRentalPeriodHours=' . $min_rent_per . '&quantity=1&lang=de&_requestUniqueId=' . getRequestUniqueId();

        $product_price = _t('Price could not be verified', true);

        $product_price_fetch = @file_get_contents($url);
        if ($product_price_fetch) {
            $product_price = json_decode($product_price_fetch);
        }

        return $product_price;
    }

    // Get prices for Serp items
    function fetchPrices($items) {
        if (empty($items)) {
            return;
        }

        $url = 'https://www.erento.com/api/price/p?_requestUniqueId=' . getRequestUniqueId();

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "Accept: application/json",
            "Content-Type: application/json",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $data = array();
        $data['lang'] = 'de';
        $data['products'] = array();

        foreach ($items as $key => $item) {
            $temp_item_array = array();
            $temp_item_array['articleId']   = $item['articleId'];
            $temp_item_array['isExternal']  = $item['isExternal'];
            $temp_item_array['productE2Id'] = $item['productE2Id'];
            $temp_item_array['productId']   = $item['productId'];
            $temp_item_array['userId']      = $item['userId'];

            array_push($data['products'], $temp_item_array);
        }

        $data = json_encode($data, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        //for debug only!
        // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // TO-DO disable for production!
        // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // TO-DO disable for production!

        $resp = curl_exec($curl);
        curl_close($curl);
        $resp = json_decode($resp);
        // echo "<pre>"; print_r($resp); echo "</pre>";

        return $resp;
    }

    // Format date with german Month
    function getFormatedDateDE($date) {
        $monthsDE = _t('MONTHS', true);

        $date = explode('-', $date);
        $day = floatval($date[2]);
        $month = $monthsDE[floatval($date[1]) - 1];
        $year = $date[0];

        return $month . ' ' . $day . ', ' . $year;
    }

    function getNotifiedSellersTableName() {
        return 'notified_sellers'; // HARDCODED
    }

    function wasSellerNotified($sellerEmail) {
        global $wpdb;
        $table_name = getNotifiedSellersTableName();

        $result = $wpdb->get_results("SELECT * FROM " . $table_name . " WHERE seller_email='" . $sellerEmail . "'");

        if ($wpdb->num_rows > 0) return true;
        else return false;
    }

    function setSellerAsNotified($sellerEmail) {
        global $wpdb;
        $table_name = getNotifiedSellersTableName();

        $timestamp = NULL;
        if (ENV === 'DEV') {
            $timestamp = date('Y-m-d H:i:s');
        }

        $data = [
            'id' => NULL,
            'seller_email' => $sellerEmail,
            'timestamp' => $timestamp
        ];

        $wpdb->insert($table_name, $data);
    }

    function sendEnqEmailToCustomer($enqFormData) {
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "Bcc: erentospinoffs@gmail.com\r\n";

        $subject = _t('Enquiry successfully submitted', true) . " - " . $enqFormData['itemName'];

        ob_start();
        include_once(SHARED_MODULES . 'inc/email-templates/customer-email.php');
        $template = ob_get_clean();
        $template = inline_html_styles($template);

        // echo $template;
        $mail_sent = wp_mail($enqFormData['customerEmail'], $subject, $template, $headers);

        return $mail_sent;
    }

    function sendEnqEmailToSeller($enqFormData) {
        if (ENV == 'PROD') {
            $seller_email = $enqFormData['sellerEmail'];
        }
        if (ENV == 'DEV') {
            $seller_email = 'erentoseller@gmail.com'; // HARDCODED
        }

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "Reply-To: " . $enqFormData['customerEmail'] . "\r\n";
        $headers .= "Bcc: erentospinoffs@gmail.com\r\n";

        $subject = _t('New Enquiry', true) . " - " . $enqFormData['itemName'];

        ob_start();
        include_once(SHARED_MODULES . 'inc/email-templates/seller-email.php');
        $template = ob_get_clean();
        $template = inline_html_styles($template);

        // echo $template;
        $mail_sent = wp_mail($seller_email, $subject, $template, $headers);

        return $mail_sent;
    }

    // Send Enquiry emails
    function sendEnqEmails($enqFormData) {
        if (isset($enqFormData['sellerEmail']) && $enqFormData['sellerEmail']) {
            $was_seller_notified = wasSellerNotified($enqFormData['sellerEmail']);
            $enqFormData['wasSellerNotified'] = $was_seller_notified;

            // Send to seller first
            $seller_mail_sent = sendEnqEmailToSeller($enqFormData);

            if ($seller_mail_sent) {
                if (!$was_seller_notified) setSellerAsNotified($enqFormData['sellerEmail']);

                // Send to customer next
                $customer_mail_sent = sendEnqEmailToCustomer($enqFormData);

                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
