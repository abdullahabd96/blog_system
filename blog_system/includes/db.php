<?php
    $servername = 'localhost';
    $username = 'root';
    $password = '';
    $db_name = 'blog_system';

    $conn = new mysqli($servername, $username, $password, $db_name);

    if($conn -> connect_error) {
        die("Database connection failed: " . $conn -> connect_error);
    }
?>