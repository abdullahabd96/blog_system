<?php
    include 'db.php';
    session_start();

    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id']) && isset($_SESSION['user'])) {
        $post_id = $_POST['post_id'];
        $user_id = $_SESSION['user']['id'];

        if(isset($_POST['like'])) {
            $stmt = $conn->prepare("INSERT IGNORE INTO likes (post_id, user_id) VALUES (?, ?) ");
            $stmt->bind_param("ii", $post_id, $user_id);
            $stmt->execute();
        } elseif(isset($_POST['unlike'])) {
            $stmt = $conn->prepare("DELETE FROM likes WHERE post_id = ? AND user_id = ?");
            $stmt->bind_param("ii", $post_id, $user_id);
            $stmt->execute();
        }
    }

    header("Location: ../posts/view_post.php?id=$post_id");
    exit;
?>