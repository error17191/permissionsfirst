<?php

// require config

require_once 'config.php';

// require actions files
$files = glob( 'actions'. '/*.php');

foreach ($files as $file) {
    require($file);
}


if ($_SERVER['REQUEST_URI'] == '/api/users' && $_SERVER['REQUEST_METHOD'] == 'GET') {
    exit();
}

if ($_SERVER['REQUEST_URI'] == '/api/me' && $_SERVER['REQUEST_METHOD'] == 'GET') {
    echo "My Info";
    exit();
}

if ($_SERVER['REQUEST_URI'] == '/api/user' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = create_user();
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}


// other endpoints


echo "404";
exit();
