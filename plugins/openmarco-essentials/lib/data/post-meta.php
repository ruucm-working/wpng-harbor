<?php

namespace Essentials\Data;

use Essentials\Core\Config;
use Essentials\Core\Context;
use Essentials\Utils\Condition;
use TitanFramework;

class Post_Meta {
	
	/**
	 * @var Context
	 */
	private $context;
	
	/**
	 * @var array
	 */
	private $settings;
	
	/**
	 * @param Context        $context
	 * @param array          $settings
	 * @param TitanFramework $titan
	 */
	public function __construct(Context $context, array $settings, TitanFramework $titan) {
		if (!is_array($settings)) {
			return;
		}
		
		$this->context  = $context;
		$this->settings = $settings;
		
		foreach ($this->settings as $post_meta_box) {
			if (isset($post_meta_box['dependency']) && !Condition::check($post_meta_box['dependency']['type'], $post_meta_box['dependency']['value'])) {
				continue;
			}
			
			$meta_box = $titan->createMetaBox(array(
				'name'      => $post_meta_box['name'],
				'post_type' => $post_meta_box['post_type'],
				
				'capability' => empty($post_meta_box['capability']) ? 'manage_options' : $post_meta_box['capability'],
				// User role
				'icon'       => empty($post_meta_box['icon']) ? 'dashicons-admin-generic' : $post_meta_box['icon'],
				// Menu icon for top level menus only
				'position'   => empty($post_meta_box['position']) ? 100.01 : $post_meta_box['position'],
				// Menu position for top level menus only
				'context'    => empty($post_meta_box['context']) ? 'normal' : $post_meta_box['context'],
				// normal, advanced, or side
				'priority'   => empty($post_meta_box['priority']) ? 'high' : $post_meta_box['priority'],
				//  high, core, default, low
				'desc'       => empty($post_meta_box['desc']) ? '' : $post_meta_box['desc']
			));
			
			foreach ((array)$post_meta_box['options'] as $meta_box_option) {
				$meta_box->createOption($meta_box_option);
			}
		}
	}
	
	/**
	 * @param Context        $context
	 * @param Config         $config
	 * @param TitanFramework $titan
	 */
	public static function init(Context $context, Config $config, TitanFramework $titan) {
		$settings = apply_filters('om_post_meta_settings', $config->post_meta);
		
		new Post_Meta($context, $settings, $titan);
	}
}