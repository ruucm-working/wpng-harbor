<?php

namespace Essentials\Data;

use Essentials\Core\Config;
use Essentials\Core\Context;
use Essentials\Utils\Condition;
use TitanFramework;

class Customizer
{

    /**
     * @var Context
     */
    private $context;

    /**
     * @var array
     */
    private $settings;

    /**
     * @param $context Context
     * @param $settings array
     * @param $titan TitanFramework
     */
    public function __construct($context, $settings, $titan)
    {
        if (!is_array($settings)) {
            return;
        }

        $this->context = $context;
        $this->settings = $settings;

        /* Customizer */

        foreach ($this->settings as $id => $parameters) {
            if(isset($parameters['dependency']) && !Condition::check($parameters['dependency']['type'], $parameters['dependency']['value'])) {
                continue;
            }

            $section = $titan->createThemeCustomizerSection(array(
                'id' => $id,
                'name' => isset($parameters['name']) ? $parameters['name'] : $id,
                'position' => isset($parameters['position']) ? $parameters['position'] : 30,
                'panel' => isset($parameters['panel']) ? $parameters['panel'] : null
            ));

            foreach ($parameters['options'] as $meta_box_option) {
                $section->createOption($meta_box_option);
            }
        }
    }

    /**
     * @param $context Context
     * @param $config Config
     * @param $titan TitanFramework
     */
    public static function init($context, $config, $titan)
    {
        $settings = apply_filters('om_customizer_settings', $config->customizer);

        new Customizer($context, $settings, $titan);
    }
}