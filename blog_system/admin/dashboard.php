<?php
    session_start();
    if(!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit;
    }
    include '../includes/db.php';
    session_abort();
?>
<?php include '../includes/header.php'; ?>
<main class="container my-5">
    <h2>Welcome, <?php echo $_SESSION['user']['username']; ?></h2>
    <a href="manage_posts.php" class="btn btn-success">Manage Posts</a>
    <a href="logout.php" class="btn btn-danger">Logout</a>
</main>
<br>
<?php include '../includes/footer.php'; ?>