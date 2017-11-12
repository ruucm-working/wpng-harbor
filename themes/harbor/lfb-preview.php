<?php

global $jal_db_version;

if (isset($jal_db_version)) {
    /** @var LFB_Core $lfb_core */
    $lfb_core = LFB_Core::instance();

    if($lfb_core && $lfb_core->dir) {
        load_template($lfb_core->dir . '/templates/lfb-preview.php');
    }
}