<?php

namespace Essentials\Data;

use Essentials\Core\Config;
use Essentials\Html\Writer;

class Menu
{
	
	/**
	 * @var array
	 */
    private static $dropdown_link_attrs = array(
        'class' => 'dropdown-toggle',
        'data-toggle' => 'dropdown',
        'role' => 'button',
        'aria-expanded' => 'true'
    );
	
	/**
	 * @var string
	 */
    private static $caret = '<span class="caret"></span>';
	
	/**
	 * @var string
	 */
    private $location;
	
	/**
	 * @var string
	 */
    private $description;
	
	/**
	 * Menu constructor.
	 *
	 * @param string $location
	 * @param string $description
	 */
    private function __construct($location, $description)
    {
        $this->location = $location;
        $this->description = $description;

        add_action('after_setup_theme', array($this, '_register_'));
        add_filter('pre_wp_nav_menu', array($this, '_render_'), 1000, 2);
    }

    public function _register_()
    {
        register_nav_menu($this->location, $this->description);
    }

    /**
     * @param $output
     * @param $args
     * @return string
     */
    public function _render_($output, $args)
    {
        $locations = get_nav_menu_locations();

        if ($output
            || !$locations
            || !isset($args->theme_location)
            || $args->theme_location !== $this->location
            || !array_key_exists($args->theme_location, $locations)
        ) {
            return $output;
        }

        $menu = wp_get_nav_menu_object($locations[$args->theme_location]);

        if (!$menu || is_wp_error($menu)) {
            return $output;
        }

        /** @var \stdClass[] $items */
        $items = wp_get_nav_menu_items($menu->term_id, array('update_post_term_cache' => false));

        if (!$items || !count($items)) {
            return $output;
        }

        _wp_menu_item_classes_by_context($items);

        self::set_item_params($items);

        if ($args->depth > 0) {
            self::filter_items($items, $args->depth);
        }

        $items = apply_filters('ome_menu_items', $items);

        /** @var \stdClass $prev */
        $prev = null;
        $html = Writer::init();

        $last_index = count($items) - 1;

        foreach ($items as $index => $item) {

            if ($prev) {
                if ($item->menu_level > $prev->menu_level) {
                    $html->ul(array('class' => 'dropdown-menu', 'role' => 'menu'));
                } else {
                    $diff = $prev->menu_level - $item->menu_level;

                    while ($diff--) {
                        $html->end(2);
                    }

                    $html->end();
                }
            }

            $classes = is_array($item->li_classes) ? $item->li_classes : array();

            if ($item->current || $item->current_item_ancestor) {
                $classes[] = 'active';
            }

            $attr = !empty($classes) ? array('class' => implode(' ', $classes)) : null;

            $html->li($attr);

            $attr = array('href' => esc_url($item->url));

            if (!empty($item->target)) {
                $attr['target'] = $item->target;
            }

            if (!empty($item->attr_title)) {
                $attr['title'] = $item->attr_title;
            }

            if (!empty($item->xfn)) {
                $attr['rel'] = $item->xfn;
            }

            if (isset($item->classes) && is_array($item->classes) && count($item->classes)) {
                $attr['class'] = implode(' ', $item->classes);
            }

            $html->a($attr, $item->title, true);

            //if (self::item_is_ancestor($item, $items)) {
            if ($index < $last_index && $item->menu_level < $items[$index + 1]->menu_level) {
                $html->a(array_merge(array('href' => esc_url('#' . sanitize_title($item->title))), self::$dropdown_link_attrs), self::$caret, true);
            }

            $prev = $item;
        }

        $html = apply_filters('ome_menu_html', $html);

        $output = Writer::init()
            ->ul(array('class' => esc_attr($args->menu_class), 'role' => 'menu'))
            ->html($html)
            ->to_string();

        $output = apply_filters('ome_menu_output', $output);

        return $output;
    }

    /**
     * @param \stdClass[] $items
     */
    private static function set_item_params(&$items)
    {
        $parents = array(0);
        $prev = -1;
        $level = 0;

        foreach ($items as &$item) {
            if ($item->menu_item_parent == $prev) {
                $parents[] = $item->menu_item_parent;
                $level++;
            } else if ($prev != -1) {
                while ($item->menu_item_parent != end($parents) && $level) {
                    array_pop($parents);
                    $level--;
                }
            }

            $item->menu_level = $level;

            $prev = $item->ID;
        }
    }
	
	/**
	 * @param \stdClass[] $items
	 * @param int $depth
	 */
    private static function filter_items(&$items, $depth)
    {
        $filtered = array();

        foreach ($items as $item) {
            if ($item->menu_level < $depth) {
                $filtered[] = $item;
            }
        }

        $items = $filtered;
    }
	
	/**
	 * @param Config $config
	 */
    public static function init(Config $config)
    {
        $menus = array_key_exists('menus', $config->common) ? $config->common['menus'] : array();

        foreach ($menus as $location => $description) {
            new Menu($location, $description);
        }
    }
}