<?php

/** @var $this OM_Carousel */

use Essentials\Data\Options;
use Essentials\Html\Writer;
use Essentials\Utils\Lightbox;

$instance = $this->settings;

$ids = empty($instance['images']) ? array() : explode(',', $instance['images']);

$size = $instance['image_size'];
$sizes = array($size);

if ($instance['on_click'] === 'lightbox') {
    $sizes = array_merge($sizes, array('full'));
}

$links = explode(',', $instance['links']);

$max_btn = $instance['type'] === 'creative' && $instance['on_click'] === 'lightbox';

$items = array();

foreach ($ids as $index => $id) {
    $html = Writer::init();

    $image = ome_get_image($id, $sizes);

    $attributes = array('itemprop' => 'image');

    if ($instance['on_click'] === 'lightbox') {
        $lightbox_atts = array(
            'data-item' => esc_url($image['full']['url']),
            'data-size' => esc_attr("{$image['full']['width']}x{$image['full']['height']}"),
        );

        if(!Options::get('lightbox_title_disable')) {
            $caption = Lightbox::get_image_caption($id);

            if (!empty($caption)) {
                $lightbox_atts['data-title'] = esc_attr($caption);
            }
        }

        $attributes['href'] = esc_url($lightbox_atts['data-item']);
        $attributes = array_merge($attributes, $lightbox_atts);
    } elseif ($instance['on_click'] === 'custom' && isset($links[$index]) && !empty($links[$index])) {
        $attributes['href'] = esc_url($links[$index]);
    }

    $html->a($attributes);

    if ($max_btn) {
        $html->span(array('class' => 'toggle-fullscreen ion-arrow-resize'), '', true);
    }

    $attributes = array(
        'src' => esc_url($image[$size]['url']),
        'alt' => esc_attr($image['alt']),
    );

    if (in_array($instance['height'], array('ratio', 'fixed'))) {
        $attributes['class'] = 'img-cover';
    }

    $html->img($attributes, true);

    $items[] = (string)$html;
}

$data = array();

if ($instance['type'] !== 'creative') {
    $class = 'slider';

    if ($instance['height'] === 'auto') {
        $height = 'auto';
    } elseif ($instance['height'] === 'ratio') {
        $height = $instance['ratio'];
    } elseif ($instance['height'] === 'fixed') {
        $heights = explode('|||', $instance['heights']);

        $current = '';
        foreach ($heights as &$value) {
            if (empty($value)) {
                $value = $current;
            } else {
                $current = $value;
            }
        }

        $height = implode(',', $heights);
    }

    if (!empty($height)) {
        $data['data-height'] = esc_attr($height);
    }
} else {
    $class = 'slider-creative';
}

if ($instance['on_click'] === 'lightbox') {
    $data['data-om-photoswipe'] = '';
}

if ($instance['show_pagination'] == 'true') {
    $data['data-pagination'] = 'true';
}

if ($instance['show_arrows'] == 'true') {
    $data['data-navigation'] = 'true';
}

if ($instance['enable_autoplay'] == 'true') {
    $data['data-auto'] = 'true';
}

if ($instance['enable_rewind'] == 'true') {
    $data['data-rewind'] = 'true';
}

if ($instance['speed']) {
    $data['data-speed'] = $instance['speed'];
}

if ($instance['rewind']) {
    $data['data-rewind-speed'] = $instance['rewind'];
}

if ($instance['on_hover_stop']) {
    $data['data-onhover-stop'] = $instance['on_hover_stop'];
}
?>

<div class="<?php echo sanitize_html_class($class) ?>" data-om-slider<?php echo Writer::get_attributes_string($data) ?>>
    <?php echo implode('', $items) ?>
</div>