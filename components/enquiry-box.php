<?php
    if ( ! defined( 'ABSPATH' ) ) exit;

    $svgs                       = get_svgs();
    $price                      = $args['price'];
    $location                   = $args['location'];
    $locations                  = $args['locations'];
    $item_name                  = $args['item_name'];
    $seller_id                  = $args['seller_id'];
    $seller_phone               = $args['seller_phone'];
    $last_link                  = $args['last_link'];
    $item_image                 = $args['item_image'];

    $hours_in_a_day             = 24;
    $pickup_hour                = 10; // enter withou leading zeros! ie. 9 instead of 09.
    $delivery_hour              = 10; // enter withou leading zeros! ie. 9 instead of 09.

    $tomorrow_native            = date('Y-m-d', strtotime("+1 day"));
    $after_tomorrow_native      = date('Y-m-d', strtotime("+2 days"));
    $next_year_native           = date('Y-m-d', strtotime("+366 days"));
    $tomorrow_formatted         = getFormatedDateDE($tomorrow_native);
    $after_tomorrow_formatted   = getFormatedDateDE($after_tomorrow_native);
?>

<enquiry-box-component>
    <form class="enquiry-form hide-after-enq-submit">
        <div class="enquiry-section time-date-section">
            <div class="section-title"><?=_t('Select period')?></div>

            <div class="date-hour-picker">
                <div class="block left">
                    <div class="labels">
                        <div class="icon"><?=$svgs['calendar']?></div>
                        <span><?=_t('Pickup')?></span>
                    </div>
                    <div class="date-hour-wrapper">
                        <div class="dh-item date pickup-date">
                            <input type="date" name="pickup_date" min="<?=$tomorrow_native?>" max="<?=$next_year_native?>" value="<?=$tomorrow_native?>">
                            <input class="dh-item-value date js-datepicker js-datepicker-pickup" value="<?=$tomorrow_formatted?>" readonly="readonly" autocomplete="off">
                        </div>
                        <div class="dh-item hour pickup-hour">
                            <input class="dh-item-native" type="time" name="pickup_hour" value="<?=str_pad($pickup_hour, 2, '0', STR_PAD_LEFT)?>:00:00" step="1800">
                            <input class="dh-item-value hour dh-item-value-pickup" value="<?=str_pad($pickup_hour, 2, '0', STR_PAD_LEFT)?>:00" readonly="readonly">

                            <div class="custom-select-wrapper">
                                <div class="custom-select">
                                    <?php
                                        for ($i=0; $i < $hours_in_a_day; $i++) {
                                            echo '<div class="custom-select-option';
                                            if ($i == $pickup_hour) echo ' selected';
                                            echo '">' . str_pad($i, 2, '0', STR_PAD_LEFT) . ':00</div>';
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="block right">
                    <div class="labels">
                        <div class="icon"><?=$svgs['calendar']?></div>
                        <span><?=_t('Return')?></span>
                    </div>
                    <div class="date-hour-wrapper">
                        <div class="dh-item date delivery-date">
                            <input class="dh-item-native" type="date" name="delivery_date" min="<?=$tomorrow_native?>" max="<?=$next_year_native?>" value="<?=$after_tomorrow_native?>">
                            <input class="dh-item-value date js-datepicker js-datepicker-delivery" value="<?=$after_tomorrow_formatted?>" readonly="readonly" autocomplete="off">
                        </div>
                        <div class="dh-item hour delivery-hour">
                            <input type="time" name="delivery_hour" value="<?=str_pad($delivery_hour, 2, '0', STR_PAD_LEFT)?>:00:00" step="1800">
                            <input class="dh-item-value hour dh-item-value-delivery" value="<?=str_pad($delivery_hour, 2, '0', STR_PAD_LEFT)?>:00" readonly="readonly">

                            <div class="custom-select-wrapper">
                                <div class="custom-select">
                                    <?php
                                        for ($i=0; $i < $hours_in_a_day; $i++) {
                                            echo '<div class="custom-select-option';
                                            if ($i == $delivery_hour) echo ' selected';
                                            echo '">' . str_pad($i, 2, '0', STR_PAD_LEFT) . ':00</div>';
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="arrow-icon"><?=$svgs['arrow']?></div>
            </div>
        </div>

        <?php
            if ($locations && count($locations) > 1) {
                echo '<div class="enquiry-section locations-picker-section">';
                    echo '<div class="select-container locations">';
                        echo '<div class="select-title">' . _t('Pickup location', true) . '</div>';
                        echo '<select name="locations" class="__outlined">';
                        foreach ($locations as $key => $locationObj) {
                            $locationString = generatePickupAddress($locationObj);
                            echo '<option value="' . $locationString . '" ';
                            echo 'data-location_ide1="' . $locationObj->ide1 . '"';
                            echo '>' . $locationString . '</option>';
                        }
                        echo '</select>';
                    echo '</div>';
                echo '</div>';
        } ?>

        <div class="enquiry-section form-section">
            <div class="section-title"><?=_t('Your information')?></div>
            <div class="input-container">
                <label><?=_t('First & last name')?></label>
                <input type="text" name="first_lastname" placeholder="<?=_t('Your first & last name')?> *">
                <div class="ss-error ss-error-first_lastname"></div>
            </div>
            <?php if (SPINOFFID !== 'zelte'): ?>
                <div class="input-container">
                    <label><?=_t('Age')?></label>
                    <input type="text" name="age" placeholder="<?=_t('Your age')?>"
                        oninput="this.value = !!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null">
                </div>
            <?php endif; ?>
            <div class="input-container">
                <label><?=_t('Email')?>*</label>
                <input type="text" name="email" placeholder="<?=_t('Your email')?> *">
                <div class="ss-error ss-error-email"></div>
            </div>
            <div class="input-container">
                <label><?=_t('Phone number')?>*</label>
                <input type="text" name="phone" placeholder="<?=_t('Your phone number')?> *">
                <div class="ss-error ss-error-phone"></div>
            </div>

            <div class="open-seller-message">
                <div class="icon"><?=$svgs['message']?></div>
                <span><?=_t('ADD_MESSAGE_TO_SELLER')?></span>
            </div>

            <div class="textarea-container seller-message hidden">
                <label><?=_t('Message to seller')?></label>
                <textarea name="message" placeholder="<?=_t('Send seller a message...')?>"></textarea>
            </div>

            <input type="email" style="display:none !important" tabindex="-1" autocomplete="off" name="second_email">
            <input type="hidden" name="item_name" value="<?=$item_name?>">
            <input type="hidden" name="item_price" value="<?=getPriceInText($price)?>">
            <input type="hidden" name="seller_id" value="<?=$seller_id?>">
            <input type="hidden" name="item_url" value="<?=getCurrentUrl()?>">
            <input type="hidden" name="item_image" value="<?=$item_image?>">
            <input type="hidden" name="location" value="<?=$location?>">
            <?php if (is_array($seller_phone) && sizeof($seller_phone) > 0) { ?>
                <input type="hidden" name="seller_phone" value='<?=json_encode($seller_phone)?>'>
            <?php } ?>

            <div class="ss-error ss-error-general"></div>

            <div class="send-enquiry-btn btn loading-dots __solid __color __bold">
                <span><?=_t('Send Enquiry')?></span>
            </div>

            <div class="non-binding-info">
                <div class="icon"><?=$svgs['free_of_charge']?></div>
                <span><?=_t('NON_BINDING')?></span>
            </div>
            <div class="terms-info"><?=_t('ENQ_TERMS')?></div>
        </div>
    </form>

    <div class="success-container">
        <div class="inner-container">
            <div class="success-title">
                <div class="icon"><?=$svgs['check']?></div>
                <span><?=_t('Enquiry submited!')?></span>
            </div>

            <div class="description">
                <?=_t('We’ve sent an enquiry confirmation to')?> <span class="fill-customer-email"></span>.
                <br>
                <strong><?=_t('Expect to receive supplier’s response shortly.')?></strong>
            </div>
        </div>

        <?php
            if (key_exists('link', $last_link)) {
                echo '<a href="' . $last_link['link'] . '" class="explore-more-btn btn loading-dots __arrow __solid __dark __bold"><span>' . _t('EXPLORE_MORE_AFTER_ENQ', true) . '</span></a>';
            }
        ?>
    </div>
</enquiry-box-component>
