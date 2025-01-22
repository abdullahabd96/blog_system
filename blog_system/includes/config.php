<?php
    $base_url = "http://localhost/blog_system/";

    $host = 'localhost';
    $user =  'root';
    $pwd = '';
    $db = 'blog_system';

    $conn = new mysqli($host, $user, $pwd, $db);

    if($conn->connect_error) {
        die("Database connection failed: ".$conn->connect_error);
    }
?>