<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use \Essentials\Html\Writer;

class TitanFrameworkOptionColorAdvanced extends TitanFrameworkOption
{

    private static $firstLoad = true;

    public $defaultSecondarySettings = array(
        'placeholder' => '', // show this when blank
    );

    /*
     * Display for options and meta
     */
    public function display()
    {
        $this->register_assets();

        $this->echoOptionHeader();

        $attributes = array(
            'id' => $this->getID(),
            'name' => $this->getID(),
            'type' => 'text',
            'value' => $this->getValue(),
            'class' => 'pluto-color-control',
            'data-color-advanced' => (isset($this->settings['alpha']) && $this->settings['alpha']) ? 'true' : 'false'
        );

        echo Writer::init()->input($attributes, true);

        $this->echoOptionFooter();

        self::$firstLoad = false;
    }

    /*
     * Display for theme customizer
     */
    public function registerCustomizerControl($wp_customize, $section, $priority = 1)
    {
        if(isset($this->settings['alpha']) && $this->settings['alpha']) {
            add_action('admin_enqueue_scripts', array($this, 'register_assets'));

            $wp_customize->add_control(new Pluto_Customize_Alpha_Color_Control($wp_customize, $this->getID(), array(
                'label' => $this->settings['name'],
                'section' => $section->getID(),
                'settings' => $this->getID(),
                'description' => $this->settings['desc'],
                'priority' => $priority,
            )));
        }else {
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, $this->getID(), array(
                'label' => $this->settings['name'],
                'section' => $section->getID(),
                'settings' => $this->getID(),
                'description' => $this->settings['desc'],
                'priority' => $priority,
            )));
        }
    }

    public function register_assets()
    {
        wp_enqueue_script('wp-color-picker');
        wp_enqueue_style('wp-color-picker');

        wp_enqueue_style('titan-color-advanced-css', plugin_dir_url(__FILE__) . 'assets/css/color-advanced.css');
        wp_enqueue_script('titan-color-advanced-js', plugin_dir_url(__FILE__) . 'assets/js/color-advanced.js', array('jquery'), null, true);
    }
}

add_action('customize_register', function ($wp_customize) {

    class Pluto_Customize_Alpha_Color_Control extends WP_Customize_Control
    {

        public $type = 'alphacolor';
        public $default = '#3FADD7';

        protected function render()
        {
            $id = 'customize-control-' . str_replace('[', '-', str_replace(']', '', $this->id));
            $class = 'customize-control customize-control-' . $this->type; ?>
            <li id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($class); ?>">
                <?php $this->render_content(); ?>
            </li>
        <?php }

        public function render_content()
        { ?>
            <label>
                <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                <input type="text"
                       data-color-advanced="true"
                       value="<?php echo intval($this->value()); ?>"
                       class="pluto-color-control" <?php $this->link(); ?>  />
            </label>
        <?php }
    }

});