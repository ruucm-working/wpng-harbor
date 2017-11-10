<?php

function om_wc_filter_fields($fields)
{
    $text_types = array('state', 'text', 'email', 'tel', 'password', 'textarea');

    foreach ($fields as &$field) {
        if (is_array($field) && (!isset($field['type']) || in_array($field['type'], $text_types, true))) {

            $field['input_class'] = array('form-control');
        }
    }

    return $fields;
}

function om_wc_filter_fields_groups($groups)
{
    foreach ($groups as &$group) {
        if (is_array($group)) {
            $group = om_wc_filter_fields($group);
        }
    }

    return $groups;
}

function om_wc_filter_button_html($html)
{
    return str_replace('class="button alt"', 'class="btn btn-flat btn-primary"', $html);
}