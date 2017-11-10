<?php

namespace Essentials\Core;

class Support
{
	
	/**
	 * @var array
	 */
    private static $defaults = array(
        'post-formats' => false,
        'post-thumbnails' => true,
        'custom-background' => false,
        'custom-header' => false,
        'automatic-feed-links' => true,
        'html5' => array('comment-list', 'comment-form', 'search-form', 'gallery', 'caption'),
        'title-tag' => true
    );
	
	/**
	 * @var array
	 */
    private $settings;

    private function __construct(array $settings)
    {
        $this->settings = array_merge(self::$defaults, $settings);

        add_action('after_setup_theme', array($this, '_setup_'));
    }
	
	/**
	 * Add theme supports
	 */
    public function _setup_()
    {
        foreach ($this->settings as $feature => $args) {
            if (!$args) {
                continue;
            }

            if (is_array($args)) {
                add_theme_support($feature, $args);
            } else {
                add_theme_support($feature);
            }
        }
    }
	
	/**
	 * @param Config $config
	 */
	public static function init(Config $config)
    {
    	$settings = array_key_exists('support', $config->common) ? $config->common['support'] : array();
        
    	new Support($settings);
    }
}