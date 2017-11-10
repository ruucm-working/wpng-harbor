<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
    <!--[if lt IE 9]>
    <script src="https://cdn.jsdelivr.net/g/html5shiv,respond"></script>
    <![endif]-->
</head>
<body <?php body_class(); ?>>

<?php global $fallback; ?>

<div class="splash" data-om-splash="400"></div>

<nav class="navmenu navmenu-fixed navmenu-expand" role="navigation" id="navigation">

    <div class="navmenu-header-overlay" data-mobile-color="#402f7e" data-current-color></div>

    <?php if (has_nav_menu('primary')) : ?>
        <div class="navmenu-nav">
            <div class="nav-wrapper">
                <div class="container">
                    <?php wp_nav_menu(array('theme_location' => 'primary', 'menu_class' => 'nav', 'depth' => 1)); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="navmenu-header">
        <div class="container">
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

            <a class="navmenu-brand" href="<?php echo esc_url(home_url('/')); ?>">
                <?php bloginfo('name') ?>
            </a>
        </div>
    </div>

</nav>

<div role="document">
    <?php locate_template($fallback, true); ?>
</div>

<footer class="content-info" role="contentinfo">
    <?php if (is_active_sidebar('primary_footer_1') || is_active_sidebar('primary_footer_2') || is_active_sidebar('primary_footer_3')) : ?>
        <div class="content-info-section">
            <div class="container" role="contentinfo">
                <div class="content-info-content">
                    <div class="row">
                        <div class="col-sm-4">
                            <?php dynamic_sidebar('primary_footer_1'); ?>
                        </div>
                        <div class="col-sm-4">
                            <?php dynamic_sidebar('primary_footer_2'); ?>
                        </div>
                        <div class="col-sm-4">
                            <?php dynamic_sidebar('primary_footer_3'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php if (is_active_sidebar('secondary_footer_1')) : ?>
        <div class="content-info-section">
            <div class="container" role="contentinfo">
                <div class="content-info-content content-info-center">
                    <div class="row">
                        <div class="col-sm-12">
                            <?php dynamic_sidebar('secondary_footer_1'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</footer>

<?php wp_footer(); ?>
</body>
</html>