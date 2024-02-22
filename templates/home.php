<?php defined( 'ABSPATH' ) || exit; ?>

<?php
    $id = get_option( 'page_for_posts' );
    $meta_data = get_field('meta_data', $id);

    buildMetaData($meta_data['title'], $meta_data['description'], false);
    if ($meta_data['robots'] != '') buildRobots($meta_data['robots']);

    get_header();
?>

<div class="container blogs-container top-spacer-container">
	<h1><?=get_queried_object()->post_title?></h1>

	<section class="blog" itemscope itemtype="http://schema.org/Blog">
		<div class="articles-container">
			<?php
                $args = array(
                    'orderby'   => 'date',
                    'posts_per_page' => 200,
                    'post_status' => 'publish',
                    'order'     => 'DESC'
                );

                // IF not default blog but category, get ID and add it to query args
                if (is_category()){
                    $category = get_queried_object();
                    $args['cat'] = $category->term_id;
                }

                $the_query_blogs = new WP_Query( $args );
				$post_counter = 0;

				if ( $the_query_blogs->have_posts() ) : while ( $the_query_blogs->have_posts() ) : $the_query_blogs->the_post();
					get_template_part( 'content', 'article', ['counter' => $post_counter] );
                    $post_counter++;
				endwhile; endif;

				/* Restore original Post Data */
				wp_reset_postdata();
			?>
		</div>
	</section>
</div>
<?php get_footer(); ?>
