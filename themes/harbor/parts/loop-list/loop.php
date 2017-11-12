<div class="hentry-list" data-image-cover="">
	<?php while (have_posts()) : the_post(); ?>

		<?php get_template_part('parts/loop-list/article') ?>

	<?php endwhile; ?>
</div>