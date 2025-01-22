<?php
    session_start();
    include '../includes/db.php';

    if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        echo "<p class='text-danger'>Access Denied!</p>";
        exit;
    }

    if(isset($_GET['id']) && is_numeric($_GET['id'])) {
        $post_id = $_GET['id'];
        $stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
        $stmt->bind_param("i", $post_id);
        $stmt->execute();
        $post = $stmt->get_result()->fetch_assoc();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'];
            $content = $_POST['content'];
            $current_timestamp = $conn->query("SELECT CURRENT_TIMESTAMP")->fetch_row()[0];
            $content = $content."\n"."Updated by ".htmlspecialchars($_SESSION['user']['username'])." at ".$current_timestamp.".";
            $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
            $stmt->bind_param("ssi", $title, $content, $post_id);
            if($stmt->execute()) {
                echo "<p class='text-success'>Post updated successfully!</p>";
            } else {
                echo "<p class='text-danger'>Failed to update post!</p>";
            }
            header("Location: ../admin/admin.php?action=manage_posts");
            exit;
        }
    } else {
        echo "<p class='text-danger'>Invalid Post ID!</p>";
        exit;
    }
    session_abort();
?>

<?php include '../includes/header.php'; ?>

<div class="container my-5">
    <h3>Edit Post</h3>
    <form method="POST">
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea rows="5" name="content" id="content" class="form-control" required>
                <?php echo htmlspecialchars($post['content']); ?>
            </textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update Post</button>
    </form>
</div>
<br>
<?php include '../includes/footer.php'; ?>