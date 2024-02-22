<?php
    if ( ! defined( 'ABSPATH' ) ) exit;

    $svgs           = get_svgs();
    $seller_info    = $args['seller_info'];
    $phone_numbers  = getSellerPhoneNumbers($seller_info);

    if ($phone_numbers) :

        $generatePhoneBtn = function($content, $class, $link = false) use ($svgs) {
            if ($link) echo '<a href="tel:' . $content . '"';
            else  echo '<div';

                echo ' class="' . $class . ' seller-phone-number-btn btn __bold __shadow __outlined">';
                echo '<div class="icon">' . $svgs['phone'] . '</div>';
                echo $content;

            if ($link) echo '</a>';
            else  echo '</div>';
        };

        $first_number_text = substr($phone_numbers[0], 0, 3) . '... ' . _t('Show phone number', true);
?>

<reveal-phone-number-component>
    <?=$generatePhoneBtn($first_number_text, 'show-seller-phone-btn')?>

    <div class="seller-phone-numbers">
        <?php
            foreach ($phone_numbers as $phone_number) {
                $generatePhoneBtn($phone_number, '', true);
            }
        ?>
    </div>
</reveal-phone-number-component>

<?php endif; ?>
