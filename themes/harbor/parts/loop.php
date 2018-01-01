<?php
use Essentials\Data\Options;

if (!om_is_essentials()) {
	$posts_page_style = 'loop-grid';
} else {
	$posts_page_style = Options::get('posts_page_style');
}
?>

<?php get_template_part('parts/loop-empty'); ?>
<?php get_template_part('parts/' . $posts_page_style . '/loop') ?>
<?php get_template_part('parts/loop-pager'); ?>

