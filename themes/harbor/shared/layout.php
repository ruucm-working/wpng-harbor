<?php use Essentials\Templates\Layout, Essentials\Data\Options; ?>
<!DOCTYPE html>
<html class="no-js" <?php language_attributes() ?>>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport"
          content="width=device-width, initial-scale=1<?php if (Options::get('disable_user_scaling')) : ?>, maximum-scale=1.0, user-scalable=no<?php endif ?>">
    <meta name="google-site-verification" content="q8rdR3iQuma3uk_31HajO_TePBkttfactzKrYudZ2oQ" />
    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_stylesheet_directory_uri() . '/assets/favicons/apple-touch-icon.png' ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_stylesheet_directory_uri() . '/assets/favicons/favicon-32x32.png' ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo get_stylesheet_directory_uri() . '/assets/favicons/favicon-16x16.png' ?>">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#ffffff">
    <?php wp_head() ?>
    <!--[if lt IE 9]>
    <script src="https://cdn.jsdelivr.net/g/html5shiv,respond"></script>
    <![endif]-->
</head>
<body <?php body_class(om_get_body_class()); ?> data-spy="scroll" data-target=".navmenu-nav" data-offset="80">

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-109584198-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-109584198-1');
</script>

<!-- Load Facebook SDK for JavaScript -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.6";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

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