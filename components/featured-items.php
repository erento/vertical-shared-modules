<?php
    if ( ! defined( 'ABSPATH' ) ) exit;

    $card_type      = array_key_exists('card_type', $args) ? $args['card_type'] : '';
    $location       = array_key_exists('location', $args) ? $args['location'] : false;
    $article_id     = array_key_exists('item_id', $args) ? $args['item_id'] : false;
    $category       = array_key_exists('category', $args) ? $args['category'] : false;
    
    if (SPINOFFID === 'sportauto' || SPINOFFID === 'oldtimer') {
        $fetched_data = $location ? fetchSerpItems($location, 16) : fetchFeaturedItems();
    } else {
        $serp_fetch_api = false;
    
        if ($category) {
            $mieten_categories = apply_filters('getMietenCategories', false );
            $serp_fetch_api = array_key_exists($category, $mieten_categories) ? $mieten_categories[$category]['erento_api_call'] : false;
        }
        
        $fetched_data = $location ? fetchSerpItems($serp_fetch_api, $location, 16) : fetchFeaturedItems();
    }

    if (
        !is_array($fetched_data) ||
        !array_key_exists('results', $fetched_data) ||
        !is_array($fetched_data['results'])
    ) return false;
    if (count($fetched_data['results']) < 1) return false;

    $prices = fetchPrices($fetched_data['results']);
    if ($location) {
        $results = $fetched_data['results'];
        unset($results[$article_id]);
        if (count($results) < 1) return false;
    } else {
        $results = shuffle_assoc($fetched_data['results']);
    }
?>
<featured-items-component class="<?=$card_type?>">
    <div class="container">
        <h2>
            <?php
                if ($location) _t('Similar items nearby');
                else _t('FRONTPAGE_FEATURED_ITEMS_HEADLINE');
            ?>
        </h2>
        <horizontal-scroller class="featured-items-wrapper">
            <?php
                $loop_count = 0;
                $card_class = '';

                foreach ($results as $article_id => $value) {
                    $loop_count++;
                    if ($loop_count > 15) break;
                    if ($loop_count > 3) $card_class = 'desktop-hidden';

                    $item_price_object = false;
                    if (is_object($prices) && property_exists($prices, $article_id)) $item_price_object = $prices->$article_id;

                    $location = false;
                    if ($value['item_data']->location->city && $value['item_data']->location->city != '') $location = $value['item_data']->location->city;

                    $featuredItemArgs = [
                        'card_class'        => $card_class,
                        'article_id'        => $value['articleId'],
                        'images'            => $value['item_data']->images,
                        'image_preset'      => 'homepage_featured_items',
                        'title'             => $value['item_data']->title,
                        'location_name'     => $location,
                        'locations_count'   => $value['item_data']->locationsCount,
                        'price'             => $item_price_object,
                        'seller_rating'     => property_exists($value['item_data'], 'sellerRating') ? $value['item_data']->sellerRating : false,
                    ];
                    if (SPINOFFID === 'sportauto') {
                        $featuredItemArgs['brand_slug'] = $value['properties']['brand'];
                        $featuredItemArgs['properties'] = [
                            'power'             => $value['properties']['power'],
                            'yearBuilt'         => $value['properties']['year_built'],
                            'transmission'      => $value['properties']['transmission'],
                        ];
                    }
                    get_shared_template_part('components/featured-item', null, $featuredItemArgs);
                }
            ?>
        </horizontal-scroller>

        <?php if (count($fetched_data['results']) > 3): ?>
            <div class="show-more-btn btn __outlined __round __small __strong hidden-xs hidden-sm"><?=_t('Load more')?></div>
        <?php endif; ?>
    </div>
</featured-items-component>
