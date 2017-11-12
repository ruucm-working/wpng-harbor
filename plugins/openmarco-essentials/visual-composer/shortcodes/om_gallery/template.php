<?php

/** @var $this OM_Gallery */

$instance = $this->settings;
$items = $this->get_items();

$this->init_gallery();
?>

<div class="<?php echo sanitize_html_class($this->hash) ?>">
    <div class="grid grid-gallery" data-om-grid="" data-image-cover=""<?php if($instance['on_click'] === 'lightbox') : ?> data-om-photoswipe=""<?php endif ?>>
        <div class="grid-sizer clearfix<?php $this->the_sizer_classes() ?>"></div>

        <?php if(is_array($items) && count($items)) foreach ($items as $index => $gallery_item) : ?>
            <?php $item = $this->init_gallery_item($index, $gallery_item); ?>

            <article class="<?php $this->the_item_classes($item) ?>">
                <div
                    class="grid-cell<?php $this->the_item_cell_classes() ?>"<?php $this->the_scroll_effect_attributes($index) ?>>
                    <?php $this->the_device_begin() ?>

                    <a class="grid-content"<?php $this->render_link_attributes() ?>>
                        <?php $this->render_image() ?>
                        <?php $this->render_caption(); ?>
                    </a>

                    <?php $this->the_device_end() ?>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</div>