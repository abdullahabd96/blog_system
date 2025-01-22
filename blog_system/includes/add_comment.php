<?php
    include 'db.php';
    session_start();

    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'], $_POST['comment']) && isset($_SESSION['user'])) {
        $post_id = $_POST['post_id'];
        $user_id = $_SESSION['user']['id'];
        $comment = htmlspecialchars($_POST['comment']);

        $stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, comment) VALUES (?, ?, ?) ");
        $stmt->bind_param("iis", $post_id, $user_id, $comment);
        $stmt->execute();
    }

    header("Location: ../posts/view_post.php?id=$post_id");
    exit;
?>