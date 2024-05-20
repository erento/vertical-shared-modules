<?php
    $meta_data = get_field('meta_data');
    if ($meta_data['title'] == '') $meta_data['title'] = get_the_title();
    if ($meta_data['description'] == '') $meta_data['description'] = get_the_excerpt();

    buildMetaData($meta_data['title'], $meta_data['description'], false);
    if ($meta_data['robots'] != '') buildRobots($meta_data['robots']);

    get_header();
    $svgs = get_svgs();
    $img_id = get_post_thumbnail_id();
?>

<div class="contact-us-hero">
    <?php if ($img_id): ?>
        <?php
            echo createResponsivePicture(
                $img_id,
                ['og_size', 'small_medium', 'medium', 'medium_large', 'large', 'huge'],
                '100vw',
                'hero-image',
                'contact us',
                'eager',
                'high'
            );
        ?>
    <?php endif; ?>
</div>

<div class="contact-container">
    <div class="container shift">
        <div class="heading-container">
            <h1><?=the_title();?></h1>
        </div>
        <div class="contact-content">
            <?php while ( have_posts() ) : the_post(); ?>
                <?=the_content();?>
            <?php endwhile ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
