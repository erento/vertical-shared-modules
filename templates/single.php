<?php
    defined( 'ABSPATH' ) || exit;

    $meta_data = get_field('meta_data');
    $img_id = get_post_thumbnail_id();
    $og_img = false;
    $main_image = false;

    if (!empty($img_id)) {
        $og_img = wp_get_attachment_image_src($img_id, 'og_size')[0];
        $main_image = wp_get_attachment_image_src($img_id, 'huge')[0];
    }

    if ($meta_data['title'] == '') $meta_data['title'] = get_the_title();
    if ($meta_data['description'] == '') $meta_data['description'] = get_the_excerpt();

    buildMetaData($meta_data['title'], $meta_data['description'], $og_img);
    if ($meta_data['robots'] != '') buildRobots($meta_data['robots']);

    get_header();

    $relatedBlogs = get_field('related_blogs');
?>

    <?php if (have_posts()): while (have_posts()) : the_post(); ?>
        <article class="blog-post" itemscope itemtype="http://schema.org/blogPost">
            <meta itemprop="url" content="<?=get_permalink($post->ID)?>"></meta>
            <?php if ($main_image): ?> <meta itemprop="image" content="<?=$main_image?>"></meta> <?php endif; ?>

            <div class="blog-header">
                <?php if ($main_image): ?>
                    <?php
                        echo createResponsivePicture(
                            get_post_thumbnail_id(),
                            ['og_size', 'small_medium', 'medium', 'medium_large', 'large', 'huge'],
                            "100vw",
                            'hero-image',
                            the_title(),
                            'eager',
                            'high'
                        );
                    ?>
                <?php endif; ?>
                <div class="blog-title-wrapper top-spacer-container">
                    <div class="container blog-narrow-container">
                        <h1 itemprop="name"><?php the_title(); ?></h1>
                    </div>
                </div>
            </div>

            <div class="container blog-narrow-container">
                <div class="blog-article-subheader">
                    <a class="back-to-blog" href="<?=get_permalink(get_option('page_for_posts'))?>">
                        <div class="btn-back"><?=_t('All magazine articles')?></div>
                    </a>
                </div>

                <div class="article-content" itemprop="articleBody">
                    <?php the_content(); ?>
                </div>
            </div>

        </article>

        <?php if (is_array($relatedBlogs)): ?>
            <section class="related-blogs-section">
                <div class="container">
                    <h2 class="h2-related-blogs"><?=_t('Related articles')?></h2>

                    <div class="related-blogs">
                        <?php
                            $i = 0;
                            foreach ($relatedBlogs as $key => $postID) {
                                if ($i == 3) break;
                                $post = get_post($postID);
                                get_template_part( 'content', 'article', ['counter' => 2] );
                                $i++;
                            }
                        ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

	<?php endwhile; ?>

    <?php else: ?>
        <article>
            <h1><?php _e( 'Sorry, nothing to display.', 'theme' ); ?></h1>
        </article>
    <?php endif; ?>

<?php get_footer(); ?>
