<?php
    session_start();
    if(!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit;
    }
    include '../includes/db.php';

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = $conn->real_escape_string($_POST['title']);
        $content = $_POST['content'];
        $author_id = $_SESSION['user']['id'];

        $stmt = $conn->prepare("INSERT INTO posts (title, content, author_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $title, $content, $author_id);

        if ($stmt->execute()) {
            $message = "Post added successfully!";
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
    $posts = $conn->query("SELECT * FROM posts WHERE author_id = " . $_SESSION['user']['id']);
    session_abort();
?>
<?php include '../includes/header.php'; ?>
<main class="container my-5">
    <h2>Manage Posts</h2>
    <?php if(isset($message)) echo "<p class='text-success'> $message </p>"; ?>
    <form method="POST" class="mb-4">
        <input type="text" name="title" class="form-control mb-3" placeholder="Title" required>
        <textarea name="content" class="form-control mb-3" placeholder="Content" required></textarea>
        <button type="submit" class="btn btn-primary">Add Post</button>
    </form>
    <h3>Your Posts</h3>
    <ul class="list-group">
        <?php while($post = $posts -> fetch_assoc()): ?>
            <li class="list-group-item">
                <strong><?php echo $post['title']; ?></strong>
                <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="delete_post.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-warning">Delete</a>
            </li>
        <?php endwhile; ?>
    </ul>
</main>
<br>
<?php include '../includes/footer.php'; ?>