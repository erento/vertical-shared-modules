<?php
    if ( ! defined( 'ABSPATH' ) ) exit;

    $component_class = '';
    $btn_text = _t('Show more...', true);

    if (!empty($args['component_class'])) $component_class = $args['component_class'];
    if (!empty($args['btn_text'])) $btn_text = $args['btn_text'];
    $text = $args['text'];
?>

<read-more-text-container-component class="<?=$component_class?>">
    <div class="paragraph"><?=$text?></div>
    <div class="read-more-btn"><?=$btn_text?></div>
    <div class="show-less-btn"><?=_t('Show less...')?></div>
</read-more-text-container-component>
