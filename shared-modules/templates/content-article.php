<?php
    defined( 'ABSPATH' ) || exit;
?>

<article class="blogs-article" itemprop="blogPosts" itemscope itemtype="http://schema.org/BlogPosting">
	<meta itemprop="url" content="<?=get_permalink()?>"></meta>
	<meta itemprop="image" content="<?=get_the_post_thumbnail_url();?>"></meta>

    <div class="blogs-article-inner">
        <a href="<?=get_permalink()?>" title="<?=get_the_title()?>" class="linkOverlay"></a>
        <div class="blog-image">
            <?php
                $counter = $args['counter'];
                $sizes = "(max-width: 767px) 90vw, (min-width: 768px) and (max-width: 991px) 710px, (min-width: 992px) and (max-width: 1199px) 450px, (min-width: 1200px) 545px";
                if ($counter > 1) $sizes = "(max-width: 767px) 90vw, (min-width: 768px) and (max-width: 991px) 340px, (min-width: 992px) and (max-width: 1199px) 290px, (min-width: 1200px) 350px";
                $loading = ($counter < 2) ? false : 'lazy';

                echo createResponsivePicture(
                    get_post_thumbnail_id(),
                    ['small_medium', 'medium', 'medium_large', 'large', 'og_size'],
                    $sizes,
                    'image-inner',
                    get_the_title(),
                    $loading
                );
            ?>
        </div>
        <div class="title-excerpt-wrapper">
            <h2 class="blog-title" itemprop="name"><?=get_the_title()?></h2>
            <div class="blog-excerpt"><?=rtrim(substr(get_the_excerpt(), 0, 140))?>...</div>
        </div>
    </div>
</article>
