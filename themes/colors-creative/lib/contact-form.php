<?php

use Essentials\Data\Options,
    Essentials\Html\Writer;

class Om_Contact_Form_7_Extends
{
    private $matches_counter;

    private static $search = '/(\[[^[]+\[\/[^\]]+])|(\[[^\]]+])/';

    private static $replacements = array(
        '<div class="row"><div class="col-sm-4">%s<div class="hidden"></div></div><div class="col-sm-4">',
        '%s<div class="hidden"></div></div><div class="col-sm-4">',
        '%s<div class="hidden"></div></div></div>'
    );

    public function __construct()
    {
        add_filter('wpcf7_default_template', array($this, 'default_template'), 20, 2);
        add_action('wpcf7_add_meta_boxes', array($this, 'add_meta_boxes')); // DEPRECATED
        add_filter('wpcf7_editor_panels', array($this, 'add_panel'));

        add_action('wpcf7_save_contact_form', array($this, 'on_save'));

        add_filter('wpcf7_contact_form_properties', array($this, 'form_template'), 10, 2);
        add_filter('wpcf7_ajax_loader', array($this, 'ajax_loader'));
    }

    public function form_template($properties, $contact_from)
    {
        if (!is_admin() && !empty($properties['form']) && Options::get('3_in_row', $contact_from->id())) {
            $this->matches_counter = 0;

            $properties['form'] = preg_replace_callback(self::$search, array($this, 'replace_field'), $properties['form'], 3);
        }

        return $properties;
    }

    public function replace_field($matches)
    {
        $result = sprintf(self::$replacements[$this->matches_counter], $matches[0]);
        $this->matches_counter++;

        return $result;
    }

    public function ajax_loader()
    {
        return get_template_directory_uri() . '/assets/img/three-dots.svg';
    }

    public function default_template($template, $prop)
    {

        if ($prop == 'form') {
            $template =
                '[text* your-name placeholder "' . esc_attr__('Your Name', 'contact-form-7') . '"]' . "\n" .
                '[email* your-email placeholder "' . esc_attr__('Your Email', 'contact-form-7') . '"]' . "\n" .
                '[text your-subject placeholder "' . esc_attr__('Subject', 'contact-form-7') . '"]' . "\n" .
                '[textarea your-message]' . "\n" .
                '[submit "' . esc_attr__('Send', 'contact-form-7') . '"]';
        }

        return $template;
    }

    public function add_meta_boxes()
    {
        add_meta_box('wpcf7ev_form_options', 'First 3 in a row', array($this, 'meta_boxes'), null, 'mail', 'core');
    }

    public function add_panel($panels) {
        $panels['PayPal'] = array(
            'title' => esc_html__( 'Colors Creative', 'colors-creative' ),
            'callback' => array($this, 'render_panel')
        );

        return $panels;
    }

    /**
     * @param $contact_form object Contact form post object
     * @return Writer
     */
    public function get_meta_boxes($contact_form) {
        $value = Options::get('3_in_row', $contact_form->id());

        $name = 'wpcf7-theme-3-in-row';

        $checkbox = array(
            'type' => 'checkbox',
            'id' => $name,
            'name' => $name
        );

        if ($value) {
            $checkbox['checked'] = 'checked';
        }

        return Writer::init()
            ->label(array('for' => $name))
            ->input($checkbox, '', true)
            ->text(esc_html__(' Show first 3 fields in one row', 'colors-creative'));
    }

    public function meta_boxes($contact_form)
    {
        $html = $this->get_meta_boxes($contact_form);

        $html->out();
    }

    public function render_panel($contact_form) {
        $meta_boxes = $this->get_meta_boxes($contact_form);

        $html = Writer::init()->fieldset()
            ->legend(null, esc_html__('Theme specific contact form options', 'colors-creative'), true)
            ->html($meta_boxes);

        $html->out();
    }

    public function on_save($contact_form)
    {
        Options::set('3_in_row', isset($_POST['wpcf7-theme-3-in-row']) && $_POST['wpcf7-theme-3-in-row'] == 'on', $contact_form->id());
    }
}

new Om_Contact_Form_7_Extends();
