<?php
    session_start();
    if(!isset($_SESSION['user'])){
        header('Location: login.php');
        exit;
    }
    include '../includes/db.php';

    $post_id = $_GET['id'];
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = $conn->real_escape_string($_POST['title']);
        $content = $_POST['content'];

        $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ? ");
        $stmt->bind_param("ssi", $title, $content, $post_id);
        $stmt->execute();
        header('Location: manage_posts.php');
    }
    $stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $post = $stmt->get_result()->fetch_assoc();
    session_abort();
?>
<?php include '../includes/header.php'; ?>
<main class="container my-5">
    <h2>Edit Post</h2>
    <form method="POST">
        <input type="text" name="title" class="form-control mb-3" value="<?php echo $post['title']; ?>" required>
        <textarea name="content" class="form-control mb-3" required><?php echo $post['content']; ?></textarea>
        <button type="submit" class="btn btn-primary">Update Post</button>
    </form>
</main>
<br>
<?php include '../includes/footer.php'; ?>