<?php

class OM_Social_Icons_Widget extends WP_Widget
{
    public function __construct()
    {
        $options = array(
            'description' => 'Display social icons inline'
        );

        parent::__construct('social-icons-widget', esc_html__('Follow us', 'colors-creative'), $options);
    }

    public function widget($args, $instance)
    {
        $target_blank = isset($instance['target_blank']) && $instance['target_blank'];

        $html = Essentials\Html\Writer::init();

        $html->text($args['before_widget']);

        if (!empty($instance['title'])) {
            $html->text($args['before_title'] . $instance['title'] . $args['after_title']);
        }

        $icons = self::get_icon_list();

        $html->ul('class="social-icons"');

        foreach ($icons as $icon) {
            $id = $icon['id'];

            if (!empty($instance[$id])) {
                $option = $instance[$id];
                $attributes = array();

                if ($id === 'email' && 0 !== strpos($option,'mailto:')) {
                    $option = 'mailto:' . antispambot(sanitize_email($option), 1);
                }

                if ($id === 'skype' && 0 !== strpos($option,'skype:')) {
                    $option = 'skype:' . htmlspecialchars($option, ENT_QUOTES) . '?chat';
                }

                if (0 === strpos($option, 'http://')) {
                    $option = esc_url(substr($option, 5));
                }

                if ($target_blank) {
                    $attributes['target'] = '_blank';
                }
                
                $attributes['href'] = $option;

                $html->li()->a($attributes)->span(array('class' => esc_attr($icon['icon'])))->end(3);
            }
        }

        $html->text($args['after_widget']);

        om_html($html);
    }

    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;

        $instance['title'] = strip_tags($new_instance['title']);

        $instance['target_blank'] = isset($new_instance['target_blank']) && $new_instance['target_blank'] === 'on';

        $icons = self::get_icon_list();

        foreach ($icons as $icon) {
            $id = $icon['id'];

            $value = strip_tags($new_instance[$id]);

            if ($id === 'email') {
                $value = sanitize_email($value);
            } else if ($id === 'skype') {
                $value = htmlspecialchars($value, ENT_QUOTES);
            } else {
                $value = esc_url_raw($value);
            }

            $instance[$id] = $value;
        }

        return $instance;
    }

    public function form($instance)
    {
        $html = Essentials\Html\Writer::init();

        $html->p()
            ->label(array('for' => esc_attr($this->get_field_id('title'))), esc_html__('Title:', 'colors-creative'), true)
            ->input(array(
                'id' => esc_attr($this->get_field_id('title')),
                'class' => 'widefat',
                'type' => 'text',
                'name' => esc_attr($this->get_field_name('title')),
                'value' => isset($instance['title']) ? esc_attr($instance['title']) : ''
            ), true)
            ->end();

        $icons = self::get_icon_list();

        foreach ($icons as $icon) {
            $html->p()
                ->label(array('for' => esc_attr($this->get_field_id($icon['id']))), esc_html($icon['name']), true)
                ->input(array(
                    'id' => esc_attr($this->get_field_id($icon['id'])),
                    'class' => 'widefat',
                    'type' => 'text',
                    'name' => esc_attr($this->get_field_name($icon['id'])),
                    'placeholder' => esc_attr($icon['placeholder']),
                    'value' => isset($instance[$icon['id']]) ? esc_attr($instance[$icon['id']]) : ''
                ), true)
                ->end();
        }

        $target_attributes = array(
            'id' => esc_attr($this->get_field_id('target_blank')),
            'class' => 'checkbox',
            'type' => 'checkbox',
            'name' => esc_attr($this->get_field_name('target_blank')),
        );

        if (isset($instance['target_blank']) && $instance['target_blank']) {
            $target_attributes['checked'] = 'checked';
        }

        $html->p()
            ->label(array('for' => esc_attr($this->get_field_id('target_blank'))))
            ->input($target_attributes, true)
            ->text(esc_html__('Open links in a new window/tab', 'colors-creative'))
            ->end();

        $html->out();
    }

    private static function get_icon_list()
    {
        return array(
            array(
                'name' => esc_html__('Email', 'colors-creative'),
                'id' => 'email',
                'placeholder' => 'name@example.com',
                'icon' => 'icon ion-email'
            ),
            array(
                'name' => esc_html__('Skype', 'colors-creative'),
                'id' => 'skype',
                'placeholder' => 'skype user',
                'icon' => 'icon ion-social-skype'
            ),
            array(
                'name' => esc_html__('RSS', 'colors-creative'),
                'id' => 'rss',
                'placeholder' => 'http://',
                'icon' => 'icon ion-social-rss'
            ),
            array(
                'name' => esc_html__('Behance', 'colors-creative'),
                'id' => 'behance',
                'placeholder' => 'http://',
                'icon' => 'icon icon-behance'
            ),
            array(
                'name' => esc_html__('Dribbble', 'colors-creative'),
                'id' => 'dribbble',
                'placeholder' => 'http://',
                'icon' => 'icon ion-social-dribbble'
            ),
            array(
                'name' => esc_html__('Facebook', 'colors-creative'),
                'id' => 'facebook',
                'placeholder' => 'http://',
                'icon' => 'icon ion-social-facebook'
            ),
            array(
                'name' => esc_html__('Flickr', 'colors-creative'),
                'id' => 'flickr',
                'placeholder' => 'http://',
                'icon' => 'icon icon-flickr'
            ),
            array(
                'name' => esc_html__('GitHub', 'colors-creative'),
                'id' => 'github',
                'placeholder' => 'http://',
                'icon' => 'icon ion-social-github'
            ),
            array(
                'name' => esc_html__('Google+', 'colors-creative'),
                'id' => 'google_plus',
                'placeholder' => 'http://',
                'icon' => 'icon ion-social-googleplus'
            ),
            array(
                'name' => esc_html__('Instagram', 'colors-creative'),
                'id' => 'instagram',
                'placeholder' => 'http://',
                'icon' => 'icon ion-social-instagram'
            ),
            array(
                'name' => esc_html__('Pinterest', 'colors-creative'),
                'id' => 'pinterest',
                'placeholder' => 'http://',
                'icon' => 'icon ion-social-pinterest'
            ),
            array(
                'name' => esc_html__('Tumblr', 'colors-creative'),
                'id' => 'tumblr',
                'placeholder' => 'http://',
                'icon' => 'icon ion-social-tumblr'
            ),
            array(
                'name' => esc_html__('Twitter', 'colors-creative'),
                'id' => 'twitter',
                'placeholder' => 'http://',
                'icon' => 'icon ion-social-twitter'
            ),
            array(
                'name' => esc_html__('LinkedIN', 'colors-creative'),
                'id' => 'linkedin',
                'placeholder' => 'http://',
                'icon' => 'icon ion-social-linkedin'
            ),
            array(
                'name' => esc_html__('Vimeo', 'colors-creative'),
                'id' => 'vimeo',
                'placeholder' => 'http://',
                'icon' => 'icon ion-social-vimeo'
            ),
            array(
                'name' => esc_html__('YouTube', 'colors-creative'),
                'id' => 'youtube',
                'placeholder' => 'http://',
                'icon' => 'icon ion-social-youtube'
            ),
            array(
                'name' => esc_html__('Yelp', 'colors-creative'),
                'id' => 'yelp',
                'placeholder' => 'http://',
                'icon' => 'icon icon-yelp'
            ),
            array(
                'name' => esc_html__('SoundCloud', 'colors-creative'),
                'id' => 'soundcloud',
                'placeholder' => 'http://',
                'icon' => 'icon icon-soundcloud'
            ),
            array(
                'name' => esc_html__('VK', 'colors-creative'),
                'id' => 'vkontakte',
                'placeholder' => 'http://',
                'icon' => 'icon icon-vkontakte'
            ),
            array(
                'name' => esc_html__('Odnoklassniki', 'colors-creative'),
                'id' => 'odnoklassniki',
                'placeholder' => 'http://',
                'icon' => 'icon icon-odnoklassniki'
            ),
            array(
                'name' => esc_html__('Any Photo Website', 'colors-creative'),
                'id' => 'photo_website',
                'placeholder' => 'http://',
                'icon' => 'icon ion-camera'
            )
        );
    }
}