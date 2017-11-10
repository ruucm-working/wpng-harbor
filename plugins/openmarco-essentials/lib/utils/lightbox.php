<?php
namespace Essentials\Utils;

class Lightbox {
	
	public static function get_image_caption($id) {
		$attachment = get_post($id);
		
		$caption = '';
		
		if ($attachment) {
			$caption = $attachment->post_title;
			
			if (!empty($attachment->post_excerpt)) {
				$caption = empty($caption)
					? $attachment->post_excerpt
					: ("$caption<br/><small>{$attachment->post_excerpt}</small>");
			}
		}
		
		return $caption;
	}
}