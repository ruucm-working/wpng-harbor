<?php use Essentials\Data\Options; ?>

<?php

$id           = om_get_current_page_id();
$section_atts = om_get_shifting_attributes('', $id);

if (!om_is_essentials()) {
	$section_class = '';
} else {
	$section_class = Options::get('blog_section_size');
}

$left_sidebar = is_active_sidebar('posts_left');
$right_sidebar = is_active_sidebar('posts_right');

?>

<?php get_template_part('parts/header'); ?>

<div class="section <?php echo esc_attr($section_class) ?>"<?php om_attributes_string($section_atts) ?>>
	<div class="container">
		<div class="section-content">

			<?php if ($left_sidebar && $left_sidebar) : ?>
				<div class="row">
					<div class="col-md-6 col-md-push-3">
						<?php get_template_part('parts/loop'); ?>
					</div>
					<div class="col-md-3 col-md-pull-6">
						<?php \Essentials\Data\Sidebar::render('posts_left') ?>
					</div>
					<div class="col-md-3">
						<?php \Essentials\Data\Sidebar::render('posts_right') ?>
					</div>
				</div>
			<?php elseif($left_sidebar) : ?>
				<div class="row">
					<div class="col-md-9 col-md-push-3">
						<?php get_template_part('parts/loop'); ?>
					</div>
					<div class="col-md-3 col-md-pull-9">
						<?php \Essentials\Data\Sidebar::render('posts_left') ?>
					</div>
				</div>

			<?php elseif($right_sidebar) : ?>
				<div class="row">
					<div class="col-md-9">
						<?php get_template_part('parts/loop'); ?>
					</div>
					<div class="col-md-3">
						<?php \Essentials\Data\Sidebar::render('posts_right') ?>
					</div>
				</div>

			<?php else : ?>
				<?php get_template_part('parts/loop'); ?>
			<?php endif ?>
		</div>
	</div>
</div>
