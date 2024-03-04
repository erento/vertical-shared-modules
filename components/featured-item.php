<?php
    if ( ! defined( 'ABSPATH' ) ) exit;

    $svgs               = get_svgs();
    $card_class         = array_key_exists('card_class', $args) ? $args['card_class'] : false;
    $article_id         = $args['article_id'];
    $brand_slug         = array_key_exists('brand_slug', $args) ? $args['brand_slug'] : false;
    $images             = $args['images'];
    $image_preset       = $args['image_preset'];
    $title              = $args['title'];
    $location_name      = $args['location_name'];
    $locations_count    = $args['locations_count'];
    $price_object       = $args['price'];
    $properties         = array_key_exists('properties', $args) ? $args['properties'] : false;
    $seller_rating      = $args['seller_rating'];
    $promoted           = array_key_exists('promoted', $args) ? $args['promoted'] : false;
?>

<horizontal-scroller-item class="<?=$card_class?>">
    <featured-item-component>
        <div class="featured-item-wrapper">
            <div class="flickity-gallery-wrapper">
                <?php
                    if ($promoted && is_object($promoted) && property_exists($promoted, 'type') && $promoted->type === "position") {
                        echo '<div class="top-promotion-badge">';
                            echo '<div class="thunder-icon">' . $svgs['thunder'] . '</div>';
                            echo '<div class="top-tag">' . _t('TOP_POSITION_BADGE', true) . '</div>';
                        echo '</div>';
                    }
                    
                    get_shared_template_part('components/flickity-gallery', null, array(
                        'images'        => $images,
                        'image_preset'  => $image_preset,
                        'article_id'    => $article_id,
                        'brand_slug'    => $brand_slug,
                        'title'         => $title,
                        'lazy_loaded'   => true,
                    ));
                ?>
            </div>

            <?php echo '<a href="' . getPdpPermalink($article_id, $brand_slug) . '" class="PDP-link">';?>
                <div class="info">
                    <div class="location-rating-wrapper">
                        <?=getLocationHtml($location_name, $locations_count)?>
                        <?=getSellerRatingBadgeHtml($seller_rating)?>
                    </div>
                    <div class="title"><?=$title?></div>
                    <?php if ($properties) echo getPropertiesHtml($properties); ?>
                    <?=getPriceHtml($price_object)?>
                </div>
            </a>
        </div>
    </featured-item-component>
</horizontal-scroller-item>
