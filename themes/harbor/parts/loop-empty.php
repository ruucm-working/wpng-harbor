<?php if (!have_posts()) : ?>
	<div class="row">
		<div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
			<?php get_search_form(); ?>
		</div>
	</div>
<?php endif; ?>
