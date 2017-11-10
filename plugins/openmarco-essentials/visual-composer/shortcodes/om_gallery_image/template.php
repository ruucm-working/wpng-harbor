<?php

/** @var $this OM_Gallery_Image */

$instance = $this->settings;
$instance['item_type'] = 'image';

echo json_encode($instance), ',';