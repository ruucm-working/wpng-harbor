
<?php $post_idx = 0; while ( have_posts() ) : the_post(); ?>

	<?php if ($post_idx%4 == 0): ?>
		<div class="columns">
	<?php endif; ?>
		<div class="column is-one-quarter">
			<div><?php the_post_thumbnail(); ?></div>
			<div><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
		</div>
	<?php if ($post_idx%4 == 3): ?>
		</div>
	<?php endif; ?>
	<p><?php $post_idx++; ?></p>
<?php endwhile; ?>
