<?php

$GLOBALS['conn'] = new mysqli('localhost', 'root', '', 'permissionsfirst');

if ($GLOBALS['conn']->connect_error) {
    die("Connection failed: " . $GLOBALS['conn']->connect_error);
}

