<?php
    if ( ! defined( 'ABSPATH' ) ) exit;

    $brand_to_exclude = array_key_exists('brand_to_exclude', $args) ? $args['brand_to_exclude'] : false;
    $location = array_key_exists('location', $args) ? $args['location'] : false;
    $css_container_class = array_key_exists('css_container_class', $args) ? $args['css_container_class'] : '';
?>

<brand-cards-component>
    <div class="container<?=$css_container_class?>">
        <horizontal-scroller class="brand-cards-wrapper">
            <?php
                $brands_and_models = apply_filters( 'getAllBrandsModels', false );

                foreach ($brands_and_models as $slug => $value_array) {
                    if ( $slug != $brand_to_exclude) {
                        get_template_part('components/brand-card', null, array(
                            'card_type' => 'normal',
                            'slug'      => $value_array['slug'],
                            'name'      => $value_array['name'],
                            'img'       => $value_array['icon'],
                            'location'  => $location,
                        ));
                    }
                }
            ?>
        </horizontal-scroller>
    </div>
</brand-cards-component>
