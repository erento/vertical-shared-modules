<?php
    if ( ! defined( 'ABSPATH' ) ) exit;
    
    $svgs = get_svgs();
    $phone_numbers = !empty($args['phone_numbers']) ? $args['phone_numbers'] : false;
?>

<mobile-contact-details-popup>
    <div class="modal-overlay"></div>
    <div class="mobile-contact-details-popup">
        <div class="close close-popup"><?=$svgs['close']?></div>
        <div class="title"><?=_t('Contact the seller')?>:</div>
        <div class="numbers">
            <?php
                foreach ($phone_numbers as $key => $phone_number) {
                    echo '<a href="tel:' . $phone_number . '" class="btn call-seller-btn-popup-mobile __solid __color __shadow __bold"><div class="icon">' . $svgs['phone'] . '</div>' . $phone_number . '</a>';
                }
            ?>
        </div>
        <div class="description"><?=_t('MENTION_ERENTO')?></div>
    </div>
</mobile-contact-details-popup>
