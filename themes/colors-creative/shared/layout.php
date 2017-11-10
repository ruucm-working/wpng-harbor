<?php use Essentials\Templates\Layout, Essentials\Data\Options; ?>
<!DOCTYPE html>
<html class="no-js" <?php language_attributes() ?>>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport"
          content="width=device-width, initial-scale=1<?php if (Options::get('disable_user_scaling')) : ?>, maximum-scale=1.0, user-scalable=no<?php endif ?>">
    <?php wp_head() ?>
    <!--[if lt IE 9]>
    <script src="https://cdn.jsdelivr.net/g/html5shiv,respond"></script>
    <![endif]-->
</head>
<body <?php body_class(om_get_body_class()); ?> data-spy="scroll" data-target=".navmenu-nav" data-offset="80">

<?php Layout::render('splash') ?>

<?php if (!Options::get('hide_navigation_bar')) : ?>
    <?php Layout::render('navigation') ?>
<?php endif ?>

<?php do_action('om_theme_before_content'); ?>

<div class="wrap" role="document">
    <?php Layout::render() ?>
</div>

<?php do_action('om_theme_after_content'); ?>

<?php if (!Options::get('hide_footer')) : ?>
    <?php Layout::render('footer') ?>
<?php endif ?>

<?php wp_footer() ?>
</body>
</html>