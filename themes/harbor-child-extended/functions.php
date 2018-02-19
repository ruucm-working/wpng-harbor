<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

/**
 * Main child theme class
 *
 * @class OM_Child_Theme
 */
final class OM_Child_Theme
{
	/**
	 * @var bool|mixed|null|string Theme version
	 */
	private $version;

	/**
	 * Setup all the things
	 */
	public function __construct()
	{
		$theme = wp_get_theme();
		$this->version = $theme->Version;

		add_action('wp_enqueue_scripts', array($this, 'enqueue_css'));
		// add_action('wp_enqueue_scripts', array($this, 'enqueue_js'));

		// Includes
		require_once(__DIR__ . '/lib/extras.php');
	}

	/**
	 * Enqueue the CSS
	 */
	public function enqueue_css()
	{
		wp_enqueue_style('child-custom-css', get_stylesheet_directory_uri() . '/assets/css/style.css', null, $this->version);
		wp_enqueue_style('child-editable-css', get_stylesheet_directory_uri() . '/style-edit.css', null, $this->version);
	}

	/**
	 * Enqueue the Javascript
	 */
	public function enqueue_js()
	{
		wp_enqueue_script('child-custom-js', get_stylesheet_directory_uri() . '/assets/js/custom.js', array('jquery'), $this->version, true);
	}
}

/**
 * Initialise
 */
add_action('init', function () {
	new OM_Child_Theme();
});

function harbor_preload_scripts() {
	wp_enqueue_script( 'classie-js', get_stylesheet_directory_uri() . '/assets/js/classie.js', false );
	wp_enqueue_script( 'snap.svg-min-js', get_stylesheet_directory_uri() . '/assets/js/snap.svg-min.js', false );
	wp_enqueue_script( 'svgLoader-js', get_stylesheet_directory_uri() . '/assets/js/svgLoader.js', false );
}
add_action( 'init', 'harbor_preload_scripts' );

function harbor_scripts() {
	// mo js animation
	wp_enqueue_script( 'harbor-mo.min', get_stylesheet_directory_uri() . '/assets/js/mojs/mo.min.js', false );
	wp_enqueue_script( 'harbor-mojs-player.min', get_stylesheet_directory_uri() . '/assets/js/mojs/mojs-player.min.js', false );
	// wp_enqueue_script( 'harbor-mojs-curve-editor.min', get_stylesheet_directory_uri() . '/assets/js/mojs/mojs-curve-editor.min.js', false );
	// AOS (Animation On Scroll)
	// wp_enqueue_script( 'harbor-aos-js', get_stylesheet_directory_uri() . '/assets/libs/aos/aos.js', false );
	// wp_enqueue_style('harbor-aos-css', get_stylesheet_directory_uri() . '/assets/libs/aos/aos.css');
	// Scroll Reveal
	wp_enqueue_script( 'harbor-scrollreveal-js', get_stylesheet_directory_uri() . '/assets/libs/scrollreveal/scrollreveal.min.js', false );
	// Harbor Scripts
	wp_enqueue_script('harbor-js', get_stylesheet_directory_uri() . '/assets/js/harbor.js', array('jquery'), false);

}
add_action( 'wp_footer', 'harbor_scripts' );


/**
 *	Change default 'projects' url slug of custom post type
 */
add_filter( 'register_post_type_args', 'custom_register_post_type_args', 10, 2 );
function custom_register_post_type_args( $args, $post_type ) {
	if ( 'project' === $post_type )
		$args['rewrite']['slug'] = 'stories';
	return $args;
}

function add_custom_rewrite_rule() {
    // First, try to load up the rewrite rules. We do this just in case
    // the default permalink structure is being used.
    if( ($current_rules = get_option('rewrite_rules')) ) {

        // Next, iterate through each custom rule adding a new rule
        // that replaces 'movies' with 'films' and give it a higher
        // priority than the existing rule.
        foreach($current_rules as $key => $val) {
            if(strpos($key, 'projects') !== false) {
                add_rewrite_rule(str_ireplace('projects', 'stories', $key), $val, 'top');   
            } // end if
        } // end foreach

    } // end if/else

    // ...and we flush the rules
    flush_rewrite_rules();

} // end add_custom_rewrite_rule
add_action('init', 'add_custom_rewrite_rule');
