<?php

$GLOBALS['conn'] = new mysqli('localhost', 'root', '123456', 'permissionsfirst');

if ($GLOBALS['conn']->connect_error) {
    die("Connection failed: " . $GLOBALS['conn']->connect_error);
}

