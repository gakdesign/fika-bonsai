<?php
// inc/lazy-load.php

add_filter('wp_get_attachment_image_attributes', function ($attr) {
    $attr['loading'] = 'lazy';
    return $attr;
});