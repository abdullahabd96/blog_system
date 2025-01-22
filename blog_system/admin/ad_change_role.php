<?php
    session_start();
    include '../includes/db.php';

    if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        echo "<p class='text-danger'>Access Denied!</p>";
        exit;
    }

    if(isset($_GET['id']) && is_numeric($_GET['id'])) {
        $user_id = $_GET['id'];
        $stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if($user['role'] === 'user') {
            $new_role = 'admin';
        } else {
            $new_role = 'user';
        }

        $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->bind_param("si", $new_role, $user_id);
        if($stmt->execute()) {
            echo "<p class='text-success'>Role changed to $new_role.</p>";
        } else {
            echo "<p class='text-danger'>Failed to change role.</p>";
        }
        $stmt->close();
    } else {
        echo "<p class='text-danger'>Invalid User ID.</p>";
    }

    header("Location: ../admin/admin.php?action=manage_users");
    exit;
?>