<?php

namespace Essentials\Data;


use Essentials\Core\Config;
use Essentials\Core\Context;

class Taxonomy extends Base_Entity
{
	
	/**
	 * @var array
	 */
    static private $defaults = array(
        'show_admin_column' => true
    );

    /**
     * @var array|string Names of the post type for the taxonomy object
     */
    private $post_types;

    /**
     * Post typeTaxonomy constructor
     * @param array $options Custom taxonomy options
     * @param string $text_domain Text domain. Unique identifier for retrieving translated strings
     *
     * $options->args description:
     * @link http://codex.wordpress.org/Function_Reference/register_taxonomy#Arguments
     */
    public function __construct(array $options, $text_domain)
    {
        parent::__construct($options, $text_domain);

        if (!array_key_exists('post_types', $options)) {
            return;
        }

        $this->post_types = $options['post_types'];
        $this->settings = $this->parse_settings($options);

        add_action('init', array($this, '_register_'));
    }


    /**
     * Register taxonomy hook
     */
    public function _register_()
    {
        register_taxonomy($this->name, $this->post_types, $this->settings);
    }

    /**
     * Compose post type settings for register
     * @param array $options Custom entity options
     * @return array
     */
    private function parse_settings(array $options)
    {
        $singular = $this->singular;
        $plural = $this->plural;
        $plural_lower = strtolower($plural);

        # Default labels
        $labels = array(
            'name' => $plural,
            'singular_name' => $singular,
            'search_items' => "Popular {$plural}",
            'popular_items' => "Search {$plural}",
            'all_items' => "All {$plural}",
            'parent_item' => "Parent {$singular}",
            'parent_item_colon' => "Parent {$singular}:",
            'edit_item' => "Edit {$singular}",
            'update_item' => "Update {$singular}",
            'add_new_item' => "Add New {$singular}",
            'new_item_name' => "New {$singular} Name",
            'separate_items_with_commas' => "Separate {$plural_lower} with commas",
            'add_or_remove_items' => "Add or remove {$plural_lower}",
            'choose_from_most_used' => "Choose from the most used {$plural_lower}",
            'menu_name' => $plural,
        );

        return parent::parse_common_settings(self::$defaults, $options, $labels);
    }
	
	/**
	 * Init taxonomy creation
	 *
	 * @param Context $context
	 * @param Config  $config
	 */
    public static function init(Context $context, Config $config)
    {
        if(!$config->taxonomies) {
            return;
        }

        foreach ($config->taxonomies as $settings) {
            new Taxonomy($settings, $context->theme->TextDomain);
        }
    }
}