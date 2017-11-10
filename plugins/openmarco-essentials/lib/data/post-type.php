<?php

namespace Essentials\Data;

use Essentials\Core\Config;
use Essentials\Core\Context;

class Post_Type extends Base_Entity {
	
	/**
	 * @var array Default post type register settings
	 * menu_position below: 5 - Posts,
	 *                      10 - Media,
	 *                      15 - Links,
	 *                      20 - Pages,
	 *                      25 - comments,
	 *                      60 - separator,
	 *                      65 - Plugins,
	 *                      70 - Users,
	 *                      75 - Tools,
	 *                      80 - Settings,
	 *                      100 - second separator
	 */
	static private $defaults = array(
		'public'        => true,
		'menu_position' => 20,
		'supports'      => array('title', 'editor', 'thumbnail', 'revisions', 'excerpt', 'page-attributes'),
		'has_archive'   => true,
		'menu_icon'     => false,
		# 'taxonomies' => array(),
		# 'exclude_from_search' => opposite of 'public'
		# 'publicly_queryable'  => {value of public},
		# 'show_ui'             => {value of public},
		# 'show_in_nav_menus'   => {value of public},
		# 'show_in_menu'        => {value of show_ui},
		# 'show_in_admin_bar'   => {value of show_in_menu}
		# 'capability_type'     => 'post',
		# 'hierarchical'        => false,
		# 'rewrite'             => true,
		# 'query_var'           => true,
		# 'can_export'          => true,
	);
	
	/**
	 * @var array The following post types are reserved and used by WordPress already
	 */
	static private $reserved_post_types = array('post', 'page', 'attachment', 'revision', 'nav_menu_item');
	
	/**
	 * @var array The following post types should not be used as they interfere with other WordPress functions
	 */
	static private $system_post_types = array('action', 'order', 'theme');
	
	/**
	 * @var string DateTime format
	 */
	static private $schedule_datetime_format;
	
	/**
	 * @var array Custom update messages
	 */
	private $messages;
	
	/**
	 * @var string Text fo view link
	 */
	private $view_text;
	
	/**
	 * @var string Text fo preview link
	 */
	private $preview_text;
	
	/**
	 * Post type constructor
	 *
	 * @param array  $options     Custom post type options
	 * @param string $text_domain Text domain. Unique identifier for retrieving translated strings
	 *                            $options->args description:
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_post_type#Arguments
	 *       Icons:
	 * @link https://developer.wordpress.org/resource/dashicons/
	 */
	public function __construct(array $options, $text_domain) {
		parent::__construct($options, $text_domain);
		
		if (in_array($options['name'], self::$system_post_types, true)) {
			return;
		}
		
		$this->settings     = $this->parse_settings($options);
		$this->messages     = $this->get_messages($options);
		$this->view_text    = __("View {$this->singular}", $text_domain);
		$this->preview_text = __("Preview {$this->singular}", $text_domain);
		
		if (!self::$schedule_datetime_format) {
			self::$schedule_datetime_format = __('M j, Y @ G:i', $text_domain);
		}
		
		add_action('init', array($this, 'register'));
		add_filter('post_updated_messages', array($this, 'update_messages'));
	}
	
	/**
	 * Register post type hook
	 */
	public function register() {
		register_post_type($this->name, $this->settings);
	}
	
	/**
	 * Post type update messages hook
	 *
	 * @param array $messages Existing post update messages.
	 *
	 * @return array Amended post update messages with new CPT update messages.
	 */
	public function update_messages($messages) {
		if (isset($this->messages)) {
			
			$post             = get_post();
			$post_type_object = get_post_type_object($this->name);
			$post_messages    = $this->messages;
			
			$post_messages[5] = isset($_GET['revision']) ? $post_messages[5] . sprintf(' %s', wp_post_revision_title((int)$_GET['revision'], false)) : false;
			$post_messages[9] .= sprintf(' <strong>%1$s</strong>.', date_i18n(self::$schedule_datetime_format, strtotime($post->post_date)));
			
			if ($post_type_object->publicly_queryable) {
				$permalink         = get_permalink($post->ID);
				$preview_permalink = esc_url(add_query_arg('preview', 'true', $permalink));
				
				$view_link    = sprintf(' <a href="%s">%s</a>', esc_url($permalink), $this->view_text);
				$preview_link = sprintf(' <a target="_blank" href="%s">%s</a>', esc_url($preview_permalink), $this->preview_text);
				
				$post_messages[1] .= $view_link;
				$post_messages[6] .= $view_link;
				$post_messages[8] .= $preview_link;
				$post_messages[9] .= $view_link;
				$post_messages[10] .= $preview_link;
			}
			
			$messages[$this->name] = $post_messages;
		}
		
		return $messages;
	}
	
	/**
	 * Compose post type settings for register
	 *
	 * @param array $options Custom entity options
	 *
	 * @return array
	 */
	private function parse_settings($options) {
		$singular = $this->singular;
		$plural   = $this->plural;
		
		# Default labels
		$labels = array(
			'name'               => $plural,
			'singular_name'      => $singular,
			'add_new'            => 'Add New',
			'add_new_item'       => "Add New {$singular}",
			'edit_item'          => "Edit {$singular}",
			'new_item'           => "New {$singular}",
			'view_item'          => "View {$singular}",
			'search_items'       => "Search {$plural}",
			'not_found'          => "No {$plural} found",
			'not_found_in_trash' => "No {$plural} found in Trash",
			'parent_item_colon'  => "Parent {$singular}:",
			'menu_name'          => $plural,
			'all_items'          => "All {$plural}",
		);
		
		$settings = parent::parse_common_settings(self::$defaults, $options, $labels);
		
		if (array_key_exists('description', $options)) {
			$settings['description'] = translate($options['description']);
		}
		
		return $settings;
	}
	
	/**
	 * Generate post type update messages
	 *
	 * @param array $options Custom post type options
	 *
	 * @return array|null Array of messages, used by WP to show update notifications
	 */
	private function get_messages($options) {
		if (!array_key_exists('messages', $options) && in_array($this->name, self::$reserved_post_types, true)) {
			return null;
		}
		
		$singular = $this->singular;
		
		$messages = array(
			0  => '', // Unused. Messages start at index 1.
			1  => "{$singular} updated.",
			2  => 'Custom field updated.',
			3  => 'Custom field deleted.',
			4  => "{$singular} updated.",
			5  => "{$singular} restored to revision from",
			6  => "{$singular} published.",
			7  => "{$singular} saved.",
			8  => "{$singular} submitted.",
			9  => "{$singular} scheduled for:",
			10 => "{$singular} draft updated."
		);
		
		if (array_key_exists('messages', $options)) {
			$messages = array_merge($messages, $options['messages']);
		}
		
		foreach ($messages as &$message) {
			$message = $this->translate($message);
		}
		
		return $messages;
	}
	
	/**
	 * Init post type creation
	 *
	 * @param Context $context
	 * @param Config  $config
	 */
	public static function init(Context $context, Config $config) {
		if (!$config->post_types) {
			return;
		}
		
		foreach ($config->post_types as $settings) {
			new Post_Type($settings, $context->theme->TextDomain);
		}
	}
}