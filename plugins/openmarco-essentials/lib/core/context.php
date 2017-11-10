<?php

namespace Essentials\Core;

class Context
{
    /**
     * @var Context
     */
    public static $instance;

    /**
     * @var \WP_Theme
     */
    public $theme;

    /**
     * @var string
     */
    public $theme_path;

    /**
     * @var string
     */
    public $theme_uri;

    /**
     * @var string
     */
    public $current_theme_path;

    /**
     * @var string
     */
    public $current_theme_uri;

    /**
     * @var bool
     */
    public $is_admin;

    /**
     * @var bool
     */
    public $is_login;

    /**
     * $var bool
     */
    public $is_child_theme;
	
	/**
	 * WordPress Context
	 */
    public function __construct()
    {
        $this->is_admin = is_admin();
        $this->is_login = $GLOBALS['pagenow'] === 'wp-login.php';

        $this->theme_uri = get_template_directory_uri();
        $this->theme_path = get_template_directory();
        $this->theme = wp_get_theme();

        $this->is_child_theme = is_child_theme();
        if ($this->is_child_theme) {
            if($parent_theme = $this->theme->parent()) {
                $this->child_theme = $this->theme;
                $this->theme = $parent_theme;
            }
            $this->child_theme_uri = get_stylesheet_directory_uri();
            $this->child_theme_path = get_stylesheet_directory();
        }
    }
}