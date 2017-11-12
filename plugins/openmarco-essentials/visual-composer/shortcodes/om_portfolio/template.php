<?php

/** @var $this OM_Portfolio */

$instance = $this->settings;
$projects = $this->get_projects();

$this->init_gallery();

?>

<div class="<?php echo sanitize_html_class($this->hash) ?>">
    <?php if ($instance['category_filter'] == 'true') : ?>
        <?php $categories = $this->get_filter_categories($projects); ?>
        <div class="grid-filters">

            <div class="row visible-xs visible-sm">
                <div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3">
                    <select class="form-control">
                        <option value="*" selected="selected"><?php esc_html_e('All', 'theme') ?></option>
                        <?php foreach ($categories as $category) : ?>
                            <option value="<?php echo esc_attr($category->slug); ?>">
                                <?php echo esc_html($category->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="btn-toolbar visible-md visible-lg">
                <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-link active">
                        <input type="radio" name="grid-option-type" value="*" checked> All
                    </label>
                    <?php foreach ($categories as $category) : ?>
                        <label class="btn btn-link">
                            <input type="radio" name="grid-option-type"
                                   value="<?php echo esc_attr($category->slug); ?>">
                            <?php echo esc_html($category->name); ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="grid" data-om-grid="" data-image-cover="">
        <div class="grid-sizer clearfix<?php $this->the_sizer_classes() ?>"></div>

        <?php foreach ($projects as $index => $project) : ?>
            <?php $item = $this->init_portfolio_item($index, $project); ?>

            <article class="<?php $this->the_item_classes($project) ?>" data-filter="<?php echo esc_attr($item['filter']) ?>">
                <div class="grid-cell<?php $this->the_item_cell_classes() ?>"<?php $this->the_scroll_effect_attributes($index) ?>>
                    <?php $this->the_device_begin() ?>

                    <a class="grid-content" href="<?php echo esc_url($item['url']) ?>"<?php $this->the_grid_cell_styles($project); ?><?php if(!$instance['hide_link_title']) : ?> title="<?php echo esc_attr($project->post_title) ?>"<?php endif ?>>
                        <?php if ($this->settings['layout'] === 'top'):?>
                            <?php $this->render_caption($project) ?>
                        <?php endif ?>
                        <?php $this->render_image($project) ?>
                        <?php if ($this->settings['layout'] !== 'top'):?>
                            <?php $this->render_caption($project) ?>
                        <?php endif ?>
                    </a>

                    <?php $this->the_device_end() ?>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</div>