<?php

$path = 'C:/laragon/www/zeromeal-api/.env';

if (file_exists($path)) {
    echo "Reading $path\n\n";
    echo file_get_contents($path);
} else {
    echo "FILE NOT FOUND: $path";
}
