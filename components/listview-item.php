<?php
    if ( ! defined( 'ABSPATH' ) ) exit;

    $svgs               = get_svgs();
    $article_id         = $args['article_id'];
    $brand_slug         = array_key_exists('brand_slug', $args) ? $args['brand_slug'] : false;
    $images             = $args['images'];
    $lazy_loaded        = $args['lazy_loaded'];
    $title              = $args['title'];
    $location_name      = $args['location_name'];
    $locations_count    = $args['locations_count'];
    $location_ide1      = $args['location_ide1'];
    $properties         = array_key_exists('properties', $args) ? $args['properties'] : false;
    $price_object       = $args['price'];
    $seller_rating      = $args['seller_rating'];
    $promoted           = array_key_exists('promoted', $args) ? $args['promoted'] : false;
?>

<listview-item-component>
    <div class="listview-item-wrapper">
        <div class="flickity-gallery-wrapper">
            <?php
                if ($promoted && is_object($promoted) && property_exists($promoted, 'type') && $promoted->type === "position") {
                    echo '<div class="top-promotion-badge">';
                        echo '<div class="thunder-icon">' . $svgs['thunder'] . '</div>';
                        echo '<div class="top-tag">' . _t('TOP_POSITION_BADGE', true) . '</div>';
                    echo '</div>';
                }
                
                get_shared_template_part('components/flickity-gallery', null, array(
                    'images'            => $images,
                    'image_preset'      => 'serp_listview_items',
                    'article_id'        => $article_id,
                    'brand_slug'        => $brand_slug,
                    'title'             => $title,
                    'location_ide1'     => $location_ide1,
                    'locations_count'   => $locations_count,
                    'lazy_loaded'       => $lazy_loaded,
                ));
            ?>
        </div>

        <?php
            if (SPINOFFID === 'sportauto') {
                echo '<a href="' . getPdpPermalink($article_id, $brand_slug) . '"';
            } else {
                echo '<a href="' . getPdpPermalink($article_id) . '"';
            }
            echo ' class="listview-info-link PDP-link"';
            if ($locations_count > 1) echo ' data-location_ide1="' . $location_ide1 . '" data-item_id="' . $article_id . '"';
            echo '>';
        ?>
            <div class="info">
                <div class="location-rating-wrapper">
                    <?=getLocationHtml($location_name, $locations_count)?>
                    <?=getSellerRatingBadgeHtml($seller_rating)?>
                </div>
                <div class="title"><?=$title?></div>
                <?php if ($properties) echo getPropertiesHtml($properties); ?>
                <div class="rating-price-wrapper">
                    <?=getSellerRatingBadgeHtml($seller_rating)?>
                    <?=getPriceHtml($price_object)?>
                </div>
            </div>
        </a>
    </div>
</listview-item-component>
