<?php

WpbakeryShortcodeParams::addField('clearfix', function () {
    return '';
});

function om_vc_clearfix($group, $weight = 0) {
    static $id;

    if(empty($id)) {
        $id = 0;
    }

    $id++;

    return array('group' => $group, 'param_name' => "clearfix-{$id}", 'type' => 'clearfix', 'edit_field_class' => 'vc_column vc_clearfix', 'weight' => $weight);
}