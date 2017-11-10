<?php

namespace Essentials\Utils;

class Once
{

    /**
     * Hooks a function on to a specific filter once
     * @param string $tag The name of the action hook
     * @param callback $fn The name of the function you wish to be called
     * @param int $priority Optional. Used to specify the order in which the functions
     *                      associated with a particular action are executed
     * @param int      $accepted_args   Optional. The number of arguments the function accepts. Default 1.
     */
    public static function add_filter($tag, $fn, $priority = 10, $accepted_args = 1)
    {
        $current_priority = has_filter($tag, $fn);

        if ($current_priority === $priority) {
            return;
        }

        if ($current_priority !== $priority) {
            remove_filter($tag, $fn);
        }

        add_filter($tag, $fn, $priority, $accepted_args);
    }

    /**
     * Hooks a function on to a specific action once
     * @param string $tag The name of the action hook
     * @param callback $fn The name of the function you wish to be called
     * @param int $priority Optional. Used to specify the order in which the functions
     *                      associated with a particular action are executed
     * @param int      $accepted_args   Optional. The number of arguments the function accepts. Default 1.
     */
    public static function add_action($tag, $fn, $priority = 10, $accepted_args = 1)
    {
        $current_priority = has_action($tag, $fn);

        if ($current_priority === $priority) {
            return;
        }

        if ($current_priority !== $priority) {
            remove_action($tag, $fn);
        }

        add_action($tag, $fn, $priority, $accepted_args);
    }
}