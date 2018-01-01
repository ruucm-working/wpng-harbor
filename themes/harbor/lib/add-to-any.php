<?php
add_filter('the_content', function ($content) {
	if (has_filter('the_content', 'A2A_SHARE_SAVE_add_to_content')) {
		$content = '<div class="section section-zero"><div class="container"><div class="section-content"><div class="row"><div class="col-sm-10 col-sm-offset-1">' . $content;
	}
	
	return $content;
}, 97);
add_filter('the_content', function ($content) {
	if (has_filter('the_content', 'A2A_SHARE_SAVE_add_to_content')) {
		$content .= '</div></div></div></div></div>';
	}
	
	return $content;
}, 99);

add_filter('the_excerpt', function ($content) {
	if (has_filter('the_excerpt', 'A2A_SHARE_SAVE_add_to_content')) {
		$content = '<div class="section section-zero"><div class="container"><div class="section-content"><div class="row"><div class="col-sm-10 col-sm-offset-1">' . $content;
	}
	
	return $content;
}, 97);
add_filter('the_excerpt', function ($content) {
	if (has_filter('the_excerpt', 'A2A_SHARE_SAVE_add_to_content')) {
		$content .= '</div></div></div></div></div>';
	}
	
	return $content;
}, 99);