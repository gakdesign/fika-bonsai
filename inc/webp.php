<?php
// inc/webp.php

add_filter('mime_types', function ($mimes) {
    $mimes['webp'] = 'image/webp';
    return $mimes;
});