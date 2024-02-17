<?php
    if ( ! defined( 'ABSPATH' ) ) exit;

    $slug   = $args['slug'];
    $name   = $args['name'];
    $img    = $args['img'];
?>

<horizontal-scroller-item>
    <location-card-component>
        <a href="<?=getLocationSerpPermalink($slug)?>">
            <?php
                echo createResponsivePicture(
                    $img,
                    ['small', 'small_medium', 'medium', 'medium_large', 'large'],
                    "(max-width: 767px) 200px, (min-width: 768px) 260px",
                    'image',
                    $name,
                    'lazy'
                );
            ?>
            <div class="title"><?=$name?></div>
        </a>
    </location-card-component>
</horizontal-scroller-item>
