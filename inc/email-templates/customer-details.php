<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<tr>
    <td class="email-padding __pt20">
        <div class="heading-two"><?=_t('Customer details')?></div>
    </td>
</tr>
<tr>
    <td class="email-padding __pb5 __pt10">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td width="120" height="26"><?=_t('Name')?></td>
                <td><?=$enqFormData['first_lastname']?></td>
            </tr>
            <tr>
                <td width="120" height="26"><?=_t('Age')?></td>
                <td>
                    <?php
                        if (empty($enqFormData['age'])) echo '/';
                        else echo $enqFormData['age'];
                    ?>
                </td>
            </tr>
            <tr>
                <td width="120" height="26"><?=_t('Phone')?></td>
                <td><strong><?=$enqFormData['customerPhone']?></strong></td>
            </tr>
            <tr>
                <td width="120" height="26"><?=_t('Email')?></td>
                <td><strong><?=$enqFormData['customerEmail']?></strong></td>
            </tr>
        </table>
    </td>
</tr>

<?php include('divider.php') ?>
