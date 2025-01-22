<?php
    session_start();
    if(!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit;
    }
    include '../includes/db.php';

    $post_id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    
    header('Location: manage_posts.php');
?>