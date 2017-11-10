<?php
/**
 * @var OM_Portfolio $this
 * @var string $project
 * @var string $preset_title
 * @var string $preset_categories
 * @var string $preset_description
 * @var string $caption_class
 */
?>

<div class="<?php echo esc_attr($caption_class) ?> animate rest-fade">
    <div class="background" style="<?php echo esc_attr(OM_Portfolio::get_project_background_styles($project, $this->settings)) ?>"></div>
    <div class="caption-content">
        <div class="top text-left">
            <?php if ($this->settings['title_show'] == 'true') : ?>
                <h3 class="title animate rest-fade<?php echo esc_attr($caption_title_class) ?>" style="<?php echo esc_attr(OM_Portfolio::get_project_title_styles($project)) ?>"><?php echo esc_html($preset_title) ?></h3>
            <?php endif; ?>

            <?php if ($this->settings['additional_info'] === 'categories') : ?>
                <p class="categories animate rest-fade<?php echo esc_attr($caption_additional_class) ?>" style="<?php echo esc_attr(OM_Portfolio::get_project_additional_styles($project)) ?>"><?php echo esc_html($preset_categories) ?></p>
            <?php endif; ?>

            <?php if ($this->settings['additional_info'] === 'description') : ?>
                <p class="description animate rest-fade<?php echo esc_attr($caption_additional_class) ?>" style="<?php echo esc_attr(OM_Portfolio::get_project_additional_styles($project)) ?>"><?php echo esc_html($preset_description) ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>