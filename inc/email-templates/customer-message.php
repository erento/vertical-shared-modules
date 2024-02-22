<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<?php if (!empty($enqFormData['message'])) { ?>

<tr>
    <td class="email-padding">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #F6F6F6; border-radius: 10px; overflow: hidden;">
            <tr>
                <td class="__pt20 __pl20 __pr20 heading-two"><?=_t('Message from customer')?></td>
            </tr>
            <tr>
                <td class="__pt10 __pl20 __pr20 __pb20"><?=$enqFormData['message']?></td>
            </tr>
        </table>
    </td>
</tr>

<?php } ?>
