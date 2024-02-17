<?php
    if ( ! defined( 'ABSPATH' ) ) exit;

    $card_type      = $args['card_type'];
    $slug           = $args['slug'];
    $name           = $args['name'];
    $img            = $args['img'];
    $location       = $args['location'];
    $brand_link     = isset($location) ? getBrandLocationSerpPermalink($slug, $location) : getBrandSerpPermalink($slug);
?>

<horizontal-scroller-item>
    <brand-card-component>
        <a href="<?=$brand_link?>">
            <img loading="lazy" src="<?=wp_get_attachment_image_src($img, 'thumbnail')[0]?>" alt="<?=$title?>">
            <div class="title"><?=$name?></div>
        </a>
    </brand-card-component>
</horizontal-scroller-item>
