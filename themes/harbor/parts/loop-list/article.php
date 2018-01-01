<?php
$type = get_post_type();
?>
<article <?php post_class('list-content'); ?>>
	<div class="row">
		<div class="col-sm-4 col-lg-3">
			<?php if (has_post_thumbnail()) : ?>
				<a class="entry-thumbnail" href="<?php echo esc_url(get_permalink()) ?>" rel="bookmark">
					<span class="entry-thumbnail-overlay"></span>
					<?php om_responsive_thumbnail(get_the_ID(), array(
						'medium-width',
						'extra-large-width'
					), array('class' => 'img-responsive')) ?>
				</a>
			<?php endif; ?>
		</div>
		<div class="col-sm-8 col-lg-9">
			<div class="entry-content">
				<header class="entry-header">
					<?php the_title(sprintf('<h2 class="entry-title h3"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>'); ?>
				</header><!-- .entry-header -->

				<div class="entry-summary">
					<?php the_excerpt(); ?>
				</div><!-- .entry-content -->

				<div class="entry-meta<?php if ($type == 'project') {
					echo ' sr-only';
				} ?>">
					<?php get_template_part('parts/entry-meta'); ?>
				</div>
			</div>

		</div>
	</div>


</article>