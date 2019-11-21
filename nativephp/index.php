<?php

// require config

require_once 'config.php';

// require actions files
$files = glob('actions' . '/*.php');

foreach ($files as $file) {
    require($file);
}


if ($_SERVER['REQUEST_URI'] == '/api/users' && $_SERVER['REQUEST_METHOD'] == 'GET') {
//    create('sadsa',[1,2,3]);
//    exit();
    $response = list_users();
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

if ($_SERVER['REQUEST_URI'] == '/api/me' && $_SERVER['REQUEST_METHOD'] == 'GET') {
    echo "My Info";
    $response = my_info();
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

if ($_SERVER['REQUEST_URI'] == '/api/user' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = create_user();
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
if ($_SERVER['REQUEST_URI'] == '/api/permissions' && $_SERVER['REQUEST_METHOD'] == 'GET') {
    $response = list_permissions();
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
if ($_SERVER['REQUEST_URI'] == '/api/permissions' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = edit_permissions();
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
if ($_SERVER['REQUEST_URI'] == '/api/articles' && $_SERVER['REQUEST_METHOD'] == 'GET') {
    $response = list_articles();
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
if ($_SERVER['REQUEST_URI'] == '/api/article' && $_SERVER['REQUEST_METHOD'] == 'GET') {
    $response = git_article();
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
if ($_SERVER['REQUEST_URI'] == '/api/my_articles' && $_SERVER['REQUEST_METHOD'] == 'GET') {
    $response = git_my_articles();
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
if ($_SERVER['REQUEST_URI'] == '/api/article' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = post_article();
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
if ($_SERVER['REQUEST_URI'] == '/api/edit_article' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = edit_article();
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}


// other endpoints

function conn()
{
    return $GLOBALS['conn'];
}

function request($key = null)
{
    if ($key == null) {
        return $_POST + (json_decode(file_get_contents('php://input'), true) ?: []);
    }
    if (isset($_POST[$key])) {
        return $_POST[$key];
    }

    $jsonData = json_decode(file_get_contents('php://input'), true);
    if (isset($jsonData[$key])) {
        return $jsonData[$key];
    }

}

echo "404";
exit();
