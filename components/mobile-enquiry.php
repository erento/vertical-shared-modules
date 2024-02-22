<?php
    if ( ! defined( 'ABSPATH' ) ) exit;

    $svgs           = get_svgs();
    $name           = $args['name'];
    $images         = $args['images'];
    $price          = $args['price'];
    $location       = $args['location'];
    $locations      = $args['locations'];
    $seller_id      = $args['seller_id'];
    $seller_phone   = $args['seller_phone'];
    $last_link      = $args['last_link'];
?>

<mobile-enquiry-component>
    <div class="component-header">
        <span class="close-mobile-enquiry">< <?=_t('Back to item')?></span>
    </div>

    <div class="enquiry-summary">
        <div class="enquiry-summary-inner">
            <?php
                $email_img_src = false;
                if (sizeof($images) > 0) {
                    $img_src_path = getStaticSrc($images[0]->src);
                    $img_src = $img_src_path . '?width=100&height=80&fit=crop';
                    $img_srcset = $img_src_path . '?width=100&height=80&fit=crop 1x, ' . $img_src_path . '?width=100&height=80&fit=crop&dpr=2 2x';
                    echo '<img loading="lazy" src="' . $img_src . '" srcset="' . $img_srcset . '">';

                    $email_img_src = getEmailImgSrc($img_src_path);
                }
            ?>
            <div class="info-wrapper">
                <div class="name"><?=$name?></div>
                <?=getPriceHtml($price)?>
            </div>
        </div>
    </div>

    <?php
        get_shared_template_part('components/enquiry-box', null, array(
            'price'         => $price,
            'item_name'     => $name,
            'location'      => $location,
            'locations'     => $locations,
            'seller_id'     => $seller_id,
            'seller_phone'  => $seller_phone,
            'last_link'     => $last_link,
            'item_image'    => $email_img_src,
        ));
    ?>

    <div class="container footer-container hide-after-enq-submit">
        <div class="btn submit-mobile-enquiry loading-dots __solid __color __big __shadow __bold">
            <span><?=_t('Send Enquiry')?></span>
        </div>
        <div class="non-binding-info">
            <div class="icon"><?=$svgs['free_of_charge']?></div>
            <span><?=_t('NON_BINDING')?></span>
        </div>
    </div>
</mobile-enquiry-component>
