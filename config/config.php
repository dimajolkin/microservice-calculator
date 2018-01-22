<?php

$env = getenv('APP_ENV');

if ($env == 'prod') {
    return include 'config.prod.php';
} else {
    return include 'config.local.php';
}