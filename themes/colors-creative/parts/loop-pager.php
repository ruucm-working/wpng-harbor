<?php
global $wp_query;
$type     = get_post_type();
$is_pages = $wp_query->max_num_pages > 1;
$is_tax   = is_category() || is_tag() || is_tax();
?>
<?php if ($is_pages || $is_tax) : ?>
	<nav class="post-nav">
		<ul class="pager">
			<?php if ($is_tax) : ?>
				<?php om_all_posts_link(); ?>
			<?php endif ?>
			<?php if ($is_pages) : ?>
				<?php
				if ($type === 'project') {
					$previous = esc_html__('Older projects', 'colors-creative');
					$next     = esc_html__('Newer projects', 'colors-creative');
				} else {
					$previous = esc_html__('Older posts', 'colors-creative');
					$next     = esc_html__('Newer posts', 'colors-creative');
				}

				$previous = '<span class="arrow-left"></span> ' . $previous;
				$next .= ' <span class="arrow-right"></span>';
				?>
				<li class="previous"><?php next_posts_link($previous); ?></li>
				<li class="next"><?php previous_posts_link($next); ?></li>
			<?php endif; ?>
		</ul>
	</nav>
<?php endif; ?>

