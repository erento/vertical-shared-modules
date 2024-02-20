<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>


<?php if (!$enqFormData['wasSellerNotified']) : ?>
    <tr>
        <td class="email-padding">
            <span class="heading"><?=_t('EMAIL_SELLER_INTRO_HEADING')?></span>
            <br><br>
            <?=_t('EMAIL_SELLER_INTRO_DESCRIPTION')?>
            <br><br>
            <strong><?=_t('EMAIL_SELLER_INTRO_END')?></strong>
            <br><br>
        </td>
    </tr>

    <?php include('divider.php') ?>
<?php endif; ?>

<tr>
    <td class="email-padding">
        <span class="heading"><?php echo $enqFormData['first_lastname'] . ' ' . _t('is interested in renting your car', true);?></span>
    </td>
</tr>
<tr>
    <td class="email-padding __pt10"><?=_t("You've received a new enquiry regarding your rental item on")?> <a href="<?=$enqFormData['itemUrl']?>"><?=getDomainName();?></a></td>
</tr>
