<div class="hentry-classic" data-image-cover="">
	<?php while (have_posts()) : the_post(); ?>

		<?php get_template_part('parts/loop/article') ?>

	<?php endwhile; ?>
</div>