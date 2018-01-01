<?php
add_filter('jetpack_sharing_display_markup', function ($sharing_content) {
	if (om_is_vc_page()) {
		$sharing_content = '<div class="section section-zero"><div class="container"><div class="section-content"><div class="row"><div class="col-sm-10 col-sm-offset-1">' . $sharing_content . '</div></div></div></div></div>';
	}

	return $sharing_content;
});