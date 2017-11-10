<?php

/**
 * Retrieves shifting attributes for section
 *
 * @param string $section_color
 * @param null $id
 * @param int $navmenu_transparency
 * @return array List of attributes
 */
function ome_get_shifting_attributes($section_color, $id = null, $navmenu_transparency = 1) {
	if(function_exists('om_get_shifting_attributes')){
		return om_get_shifting_attributes($section_color, $id, $navmenu_transparency);
	} else {
		return array();
	}
}

/**
 * Retrieves image info for given image sizes
 *
 * @param int $id Image attachment ID.
 * @param string $sizes
 * @return array
 */
function ome_get_image($id, $sizes = 'full'){
	if(function_exists('om_get_image')){
		return om_get_image($id, $sizes);
	} else {
		return array();
	}
}

function ome_get_image_sizes_list(){
	if(function_exists('om_get_image_sizes_list')){
		return om_get_image_sizes_list();
	} else {
		return array();
	}
}

function ome_responsive_image($id, $sizes = array(), $attr = array()){
	if(function_exists('om_responsive_image')){
		return om_responsive_image($id, $sizes, $attr);
	} else {
		return array();
	}
}
function ome_responsive_thumbnail($post_id, array $sizes = array(), $attr = array()){
	if(function_exists('om_responsive_thumbnail')){
		return om_responsive_thumbnail($post_id, $sizes, $attr);
	} else {
		return '';
	}
}

/**
 * Retrieves categories names for given a post ID or post object
 *
 * @param null $post Optional. Post ID or post object. Defaults to global $post.
 * @return string Categories names as string
 */
function ome_get_categories_names($post = null)
{
	if(function_exists('om_get_categories_names')){
		return om_get_categories_names($post);
	} else {
		return '';
	}
}

/**
 * Check if color dark or light
 *
 * @param string $color
 * @return bool
 */
function ome_is_dark($color)
{
	if(function_exists('om_is_dark')){
		return om_is_dark($color);
	} else {
		return false;
	}
}
