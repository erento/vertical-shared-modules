<?php
    if ( ! defined( 'ABSPATH' ) ) exit;

    $svgs                   = get_svgs();
    $location_to_exclude    = array_key_exists('location_to_exclude', $args) ? $args['location_to_exclude'] : false;
    $css_component_class    = array_key_exists('css_component_class', $args) ? $args['css_component_class'] : '';
?>

<location-cards-component>
    <div class="horizontal-scroller-controls">
        <div class="btn prev __dark __small disabled"><?=$svgs['caret']?></div>
        <div class="btn next __dark __small"><?=$svgs['caret']?></div>
    </div>

    <horizontal-scroller class="location-cards-wrapper<?=$css_component_class?>">
        <?php
            $locations = apply_filters('getAllLocations', false);

            foreach ($locations as $slug => $value_array) {
                if (!$value_array['searchable']) continue;

                if ($slug != $location_to_exclude) {
                    get_shared_template_part('components/location-card', null, array(
                        'slug' => $slug,
                        'name' => $value_array['name'],
                        'img' => $value_array['image'],
                    ));
                }
            }
        ?>
    </horizontal-scroller>
</location-cards-component>
