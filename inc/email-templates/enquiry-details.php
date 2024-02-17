<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<?php include('divider.php') ?>

<!-- ENQUIRY DETAILS TABLE -->
<tr>
    <td class="email-padding __pb15">
        <a href="<?=$enqFormData['itemUrl']?>" style="color:#333; text-decoration: underline;">
            <strong><?=$enqFormData['itemName']?></strong>
        </a>
    </td>
</tr>
<tr>
    <td class="email-padding responsive-table">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr class="responsive-table">
                <td class="item-image-cell __pr15" width="180" valign="center" align="center">
                    <a href="<?=$enqFormData['itemUrl']?>">
                        <img class="item-image"
                            width="180"
                            style="clear:both;display:block;height:auto;margin-right:10px;max-height:100px;max-width:180px;outline:0;text-decoration:none;width:auto;"
                            src="<?=$enqFormData['itemImage']?>">
                    </a>
                </td>
                <td class="item-info-cell">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td><?=_t('Pickup')?></td>
                            <td align="right"><strong><?=$enqFormData['pickup_date']?></strong> (<?=$enqFormData['pickup_hour']?>)</td>
                        </tr>
                        <tr>
                            <td class="__pt5"><?=_t('Return')?></td>
                            <td class="__pt5" align="right"><strong><?=$enqFormData['delivery_date']?></strong> (<?=$enqFormData['delivery_hour']?>)</td>
                        </tr>
                        <tr>
                            <td class="__pt5"><?=_t('Price')?></td>
                            <td class="__pt5" align="right"><strong><?=$enqFormData['itemPrice']?></strong></td>
                        </tr>

                        <?php if (!empty($enqFormData['location'])) {?>
                            <tr>
                                <td class="__pt5"><?=_t('Location')?></td>
                                <td class="__pt5" align="right"><strong><?=$enqFormData['location']?></strong></td>
                            </tr>
                        <?php } ?>

                    </table>
                </td>
            </tr>
        </table>
    </td>
</tr>
<!-- END OF ENQUIRY DETAILS TABLE -->

<?php include('divider.php') ?>
