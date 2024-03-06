<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<tr>
    <td class="email-padding">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="responsive-table">
            <tbody class="responsive-table">
                <tr class="responsive-table">
                    <td width="48%" valign="center" align="center" class="responsive-table">
                        <!-- BUTTON -->
                        <table class="button-table" width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                            <td height="50" valign="center" align="center" bgcolor="<?=$btnColor?>" style="border-radius: 6px;">
                                <!-- TO-DO (Phase 2) add dates&hours, item name in the body of email. -->
                                <a href='mailto:<?=$enqFormData['customerEmail']?>?subject=Neuigkeiten zu Ihrer Anfrage: <?=$enqFormData['itemName']?>' target="_blank" class="button-link"><?=_t('EMAIL_REPLY')?></a>
                            </td>
                            </tr>
                        </table>
                        <!-- END OF BUTTON -->
                    </td>
                    <td></td>
                    <td class="responsive-table second-cta-button" width="48%" valign="center" align="center">
                        <!-- BUTTON -->
                        <table class="button-table" width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td height="50" valign="center" align="center" bgcolor="<?=$btnColor?>" style="border-radius: 6px;">
                                    <a href="tel:<?=$enqFormData['customerPhone']?>" target="_blank" class="button-link"><?=_t('CALL_CUSTOMER')?></a>
                                </td>
                            </tr>
                        </table>
                        <!-- END OF BUTTON -->
                    </td>
                </tr>
            </tbody>
        </table>
    </td>
</tr>
