<?php
    if ( ! defined( 'ABSPATH' ) ) exit;

    $article_id         = $args['article_id'];
    $brand_slug         = array_key_exists('brand_slug', $args) ? $args['brand_slug'] : false;
    $images             = $args['images'];
    $image_preset       = $args['image_preset'];
    $title              = $args['title'];
    $location_ide1      = array_key_exists('location_ide1', $args) ? $args['location_ide1'] : false;
    $locations_count    = array_key_exists('locations_count', $args) ? $args['locations_count'] : false;
    $lazy_loaded        = array_key_exists('lazy_loaded', $args) ? $args['lazy_loaded'] : true;
    $pdpPermalink       = getPdpPermalink($article_id, $brand_slug);

    $selectedLocation   = false;
    if ($location_ide1 && $locations_count > 1) $selectedLocation = 'data-location_ide1="' . $location_ide1 . '" data-item_id="' . $article_id . '" ';

    $image_presets = [
        'homepage_featured_items' => [
            'srcset' => [
                ['width=294&height=190&fit=crop', '294w'],
                ['width=360&height=210&fit=crop', '360w'],
                ['width=500&height=297&fit=crop', '500w'],
                ['width=716&height=420&fit=crop', '716w']
            ],
            'sizes' => '(max-width: 499px) 78vw, (min-width: 500px) and (max-width: 767px) 86vw, (min-width: 768px) and (max-width: 991px) 360px, (min-width: 992px) and (max-width: 1199px) 294px, (min-width: 1200px) 358px'
        ],
        'serp_listview_items' => [
            'srcset' => [
                ['width=335&height=225&fit=crop', '335w'],
                ['width=360&height=240&fit=crop', '360w'],
                ['width=727&height=460&fit=crop', '727w'],
                ['width=500&height=324&fit=crop', '500w'],
                ['width=400&height=264&fit=crop', '400w']
            ],
            'sizes' => '(max-width: 767px) 88vw, (min-width: 768px) 360px'
        ],
        'eserp_featured_items' => [
            'srcset' => [
                ['width=294&height=190&fit=crop', '294w'],
                ['width=343&height=210&fit=crop', '343w'],
                ['width=358&height=210&fit=crop', '358w'],
                ['width=500&height=297&fit=crop', '500w'],
                ['width=716&height=420&fit=crop', '716w']
            ],
            'sizes' => '(max-width: 499px) 88vw, (min-width: 500px) and (max-width: 767px) 92vw, (min-width: 768px) and (max-width: 991px) 343px, (min-width: 992px) and (max-width: 1199px) 294px, (min-width: 1200px) 358px'
        ]
    ];
?>

<flickity-gallery-component class="card-flickity">
    <?php
        $conter = 0;
        $firstImage = false;

        if (sizeof($images) > 0) {
            foreach ($images as $key => $image) {
                $image_src = getStaticSrc($image->src);
                $selected_preset = $image_presets[$image_preset];
                $smallest_src = getSmallestSrc($selected_preset['srcset'], $image_src);
                $srcset_string = getSrcsetString($selected_preset['srcset'], $image_src);

                echo '<div class="slide">';
                    echo '<a href="' . $pdpPermalink . '" class="linkOverlay PDP-link"';
                    if ($selectedLocation) echo $selectedLocation;
                    echo '></a>';

                    $image_html = '<img ';
                        if ($lazy_loaded === false && $firstImage === false) {
                            $image_html .= 'src="' . $smallest_src . '" ';
                            $image_html .= 'srcset="' . $srcset_string . '"';
                        } else {
                            $image_html .= 'loading="lazy" ';
                            $image_html .= 'data-flickity-lazyload-src="' . $smallest_src . '" ';
                            $image_html .= 'data-flickity-lazyload-srcset="' . $srcset_string . '"';
                        }
                        $image_html .= 'sizes="' . $selected_preset['sizes'] . '" ';
                        $image_html .= 'alt="' . $title . '"';
                    $image_html .= '>';

                    echo $image_html;
                    if (!$firstImage) $firstImage = $image_html;
                echo '</div>';

                if ($conter == 5) break;
                $conter++;
            }

            echo '<div class="slide view-item-slide">';
                echo $firstImage;
                echo '<a href="' . $pdpPermalink . '" class="btn __small __strong PDP-link"';
                if ($selectedLocation) echo $selectedLocation;
                echo '>' . _t('View more', true) . '</a>';
            echo '</div>';
        } else {
            echo '<a href="' . $pdpPermalink . '" class="PDP-link no-photo-link"';
            if ($selectedLocation) echo $selectedLocation;
            echo '><div class="no-photo-slide">' . _t('No photo', true) . '</div></a>';
        }
    ?>
</flickity-gallery-component>
