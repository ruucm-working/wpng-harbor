<div class="grid hentry-grid" data-image-cover="">
	<?php $index = 0; ?>
	<?php while (have_posts()) : the_post(); ?>
		<?php if (($index % 6) === 0) : ?>
			<div class="clearfix hidden-xs"></div>
		<?php elseif (($index % 3) === 0) : ?>
			<div class="clearfix visible-md visible-lg"></div>
		<?php elseif (($index % 2) === 0) : ?>
			<div class="clearfix visible-sm"></div>
		<?php endif ?>
		<?php $index++; ?>

		<div class="grid-item col-xs-12 col-sm-6 col-md-4 row-xs-12 row-sm-6 row-md-4">
			<div class="grid-cell grid-cell-full">
				<article <?php post_class('grid-content'); ?>>
					<?php if (has_post_thumbnail()) : ?>
						<?php om_responsive_thumbnail(get_the_ID(), array('medium-width', 'extra-large-width'), array('class' => 'img-cover')) ?>
					<?php endif; ?>

					<?php get_template_part('parts/loop-grid/caption') ?>
				</article>

			</div>
		</div>

	<?php endwhile; ?>
</div>