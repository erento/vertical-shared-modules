<?php
    if ( ! defined( 'ABSPATH' ) ) exit;

    $seller_info = $args['seller_info'];
?>

<seller-info-box-component>
    <div class="title"><?=_t('Contact supplier directly')?></div>
    <?=generateSellerCompanyInfo($seller_info)?>

    <?php
        get_shared_template_part('components/reveal-phone-number', null, array(
            'seller_info' => $seller_info,
        ));
    ?>
</seller-info-box-component>
