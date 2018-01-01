<?php
use Essentials\Data\Options;

$id = om_get_current_page_id();
?>

<?php do_action('om_theme_before_footer'); ?>

<footer class="content-info" role="contentinfo">
    <?php do_action('om_theme_before_footer_sidebars'); ?>

    <?php if (Options::get('sidebar_primary_footer_enabled')) : ?>
        <?php do_action('om_theme_before_footer_primary_sidebar'); ?>

        <?php
        $shifting_atts = om_get_shifting_attributes(Options::get('footer_primary_background_color_current', $id), $id);
        $primary_container_class = Options::specified('sidebar_primary_container') ? Options::get('sidebar_primary_container') : 'container';
        ?>
        <div class="content-info-section content-info-primary"<?php om_attributes_string($shifting_atts) ?>>
            <div class="<?php echo sanitize_html_class($primary_container_class) ?>">
                <div
                    class="content-info-content<?php if (Options::get('sidebar_primary_footer_centered')) echo ' content-info-center' ?>">
                    <?php \Essentials\Data\Sidebar::render('primary_footer') ?>
                </div>
            </div>
        </div>

        <?php do_action('om_theme_after_footer_primary_sidebar'); ?>
    <?php endif; ?>
    <?php if (Options::get('sidebar_secondary_footer_enabled')) : ?>
        <?php do_action('om_theme_before_footer_secondary_sidebar'); ?>

        <?php
        $shifting_atts = om_get_shifting_attributes(Options::get('footer_secondary_background_color_current', $id), $id);
        $secondary_container_class = Options::specified('sidebar_secondary_container') ? Options::get('sidebar_secondary_container') : 'container';
        ?>
        <div class="content-info-section content-info-secondary"<?php om_attributes_string($shifting_atts) ?>>
            <div class="<?php echo sanitize_html_class($secondary_container_class) ?>">
                <div
                    class="content-info-content<?php if (Options::get('sidebar_secondary_footer_centered')) echo ' content-info-center' ?>">
                    <?php \Essentials\Data\Sidebar::render('secondary_footer') ?>
                </div>
            </div>
        </div>

        <?php do_action('om_theme_after_footer_secondary_sidebar'); ?>
    <?php endif; ?>

    <?php do_action('om_theme_after_footer_sidebars'); ?>
</footer>

<?php do_action('om_theme_after_footer'); ?>