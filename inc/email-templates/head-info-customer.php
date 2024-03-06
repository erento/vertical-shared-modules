<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<tr>
    <td class="email-padding">
        <span class="heading"><?=_t('Your enquiry was successfully sent to supplier.')?></span>
    </td>
</tr>
<tr>
    <td class="email-padding __pt10"><strong>
        <?=_t('Expect to receive supplier’s response shortly.')?></strong>
        <br>
        <?php
            if (!empty($enqFormData['sellerPhone'])) _t("You can also call the supplier if you don't get a response:");
        ?>
    </td>
</tr>

<?php if (!empty($enqFormData['sellerPhone'])) { ?>
    <tr>
        <td align="center" class="email-padding __pt20 __pb10">
            <?php
                $i = 0;
                foreach ($enqFormData['sellerPhone'] as $key => $phone_number) {
                    $i++;
                    if ($i > 1) {
                        echo '<br>';
                    }
            ?>
                    <!-- BUTTON -->
                    <table class="button-table" width="280" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td height="50" valign="center" align="center" bgcolor="<?=$btnColor?>" style="border-radius: 6px;">
                                <a href="tel:<?=$phone_number?>" target="_blank" class="button-link"><?=$phone_number?></a>
                            </td>
                        </tr>
                    </table>
                    <!-- END OF BUTTON -->
            <?php } ?>
        </td>
    </tr>
<?php } ?>
