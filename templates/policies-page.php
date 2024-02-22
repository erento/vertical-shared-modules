<?php
    $meta_data = get_field('meta_data');
    if ($meta_data['title'] == '') $meta_data['title'] = get_the_title();
    if ($meta_data['description'] == '') $meta_data['description'] = get_the_excerpt();

    buildMetaData($meta_data['title'], $meta_data['description'], false);
    if ($meta_data['robots'] != '') buildRobots($meta_data['robots']);

    get_header();
?>

	<div class="container page-container top-spacer-container">
		<div class="policies-container">
			<h1><?php the_title(); ?></h1>

			<?php if (have_posts()): while (have_posts()) : the_post(); ?>

				<?php the_content(); ?>

			<?php endwhile; ?>

			<?php else: ?>

				<h2><?php _e( 'Sorry, nothing to display.', 'html5blank' ); ?></h2>

			<?php endif; ?>
		</div>
	</div>

<?php get_footer(); ?>
