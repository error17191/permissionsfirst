<?php

$conn = new mysqli('localhost', 'root', '', 'permissionsfirst');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

