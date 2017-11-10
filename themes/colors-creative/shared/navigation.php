<?php use Essentials\Data\Options; ?>

<?php
$id = om_get_current_page_id();

$logos  = array(
	'navigation_logo'       => 'default',
	'navigation_logo_dark'  => 'dark',
	'navigation_logo_light' => 'light',
);
$brands = array();

foreach ($logos as $option => $class) {
	if (Options::specified($option)) {
		$image_id = Options::get($option);
		
		if (is_numeric($image_id)) {
			$image     = om_get_image($image_id);
			$image_url = $image['full']['url'];
		}
	}
	$image_url    = apply_filters('om_navigation_logo', isset($image_url) ? $image_url : '', $class);
	$key          = !empty($image_url) ? $image_url : 'title';
	$brands[$key] = (isset($brands[$key]) ? $brands[$key] . ' ' : '') . $class;
}

$nav_classes = array('navmenu', Options::get('navigation_type'));

if (Options::get('navigation_type') === 'navmenu-classic' && om_is_wc_nav_cart()) {
	$nav_classes[] = 'navmenu-classic-cart';
}

if (Options::get('navigation_fixed')) {
	$nav_classes[] = 'navmenu-fixed';
}

if (!Options::get('navigation_overlay_current', $id)) {
	$nav_classes[] = 'navmenu-expand';
}

$logoText = Options::get('navigation_logo_text');

if (!$logoText) {
	$logoText = get_bloginfo('name', 'display');
}

?>

<?php do_action('om_theme_before_navigation'); ?>

<nav class="<?php echo esc_attr(implode(' ', $nav_classes)) ?>" role="navigation" id="navigation">

    <div class="navmenu-header-overlay" data-classchange="background-light background-dark"
         data-classchange-target=".navmenu-header"></div>
	
	<?php if (has_nav_menu('primary')) : ?>
        <div class="navmenu-nav">
            <div class="nav-wrapper">
				<?php if (Options::specified('navigation_nav_background_image')) : ?>
                    <div class="nav-background nav-background-image"></div>
				<?php endif; ?>

                <div class="nav-background nav-background-overlay" data-classchange="background-light background-dark"
                     data-classchange-target=".navmenu-nav"></div>

                <div class="<?php echo sanitize_html_class(Options::get('navigation_container')) ?>">
					<?php wp_nav_menu(array('theme_location' => 'primary', 'menu_class' => 'nav', 'depth' => 2)); ?>
                </div>
            </div>
        </div>
	<?php endif; ?>

    <div class="navmenu-header">
        <div class="<?php echo sanitize_html_class(Options::get('navigation_container')) ?>">
			<?php if (has_nav_menu('primary')) : ?>
                <a class="navmenu-toggle"
                   data-toggle-class="in"
                   data-target="#navigation"
                   data-on-delay="10"
                   data-on-class="pre-in"
                   data-off-delay="400"
                   data-off-class="out">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
			<?php endif; ?>
			
			<?php if (om_is_wc_nav_cart()) : ?>
                <a class="navmenu-cart hidden-xs" href="<?php echo esc_url(wc_get_cart_url()) ?>">
                    <span class="<?php echo om_get_navigation_shop_icon_class() ?>"></span>
                    <span class="count"
                          data-wc-cart-count="<?php echo WC()->cart->get_cart_contents_count() ?>"><?php if (!WC()->cart->is_empty()) {
							echo WC()->cart->get_cart_contents_count();
						} ?></span>
                </a>
			<?php endif; ?>
			
			<?php if (!Options::get('navigation_logo_hide')) : ?>
				<?php
				$link = Options::specified('link_site_logo') ? Options::get('link_site_logo') : home_url('/');
				?>

                <a class="navmenu-brand" href="<?php echo esc_url($link); ?>">
					<?php foreach ($brands as $value => $classes) : ?>
						<?php if ($value !== 'title') : ?>
                            <img class="logo <?php echo esc_attr($classes) ?>"
                                 height="50"
                                 src="<?php echo esc_url($value) ?>"
                                 alt="<?php esc_attr(get_bloginfo('name', 'display')) ?>"/>
						<?php else : ?>
                            <span class="logo <?php echo esc_attr($classes) ?>">
                                <?php echo esc_html($logoText) ?>
                            </span>
						<?php endif; ?>
					<?php endforeach; ?>
                </a>
			<?php endif; ?>
        </div>
    </div>

</nav>

<?php do_action('om_theme_after_navigation'); ?>
