<?php
    include '../includes/db.php';

    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $post_id = $_GET['id'];
        $stmt = $conn->prepare("SELECT p.*, u.username AS author FROM posts p JOIN users u ON p.author_id = u.id WHERE p.id = ?");
        $stmt->bind_param("i", $post_id);
        $stmt->execute();
        $post = $stmt->get_result()->fetch_assoc();
    } else {
        echo "<p class='text-danger'><a href='../admin/login.php'>Login </a>to like or comment!</p>";
        exit;
    }

    $Comments_stmt = $conn->prepare("SELECT c.comment, c.created_at, u.username FROM comments c JOIN users u ON c.user_id = u.id WHERE c.post_id = ? ORDER BY c.created_at DESC");
    $Comments_stmt->bind_param("i", $post_id);
    $Comments_stmt->execute();
    $comments = $Comments_stmt->get_result();

    $liked = false;
    if (isset($_SESSION['user'])) {
        $user_id = $_SESSION['user']['id'];
        $like_stmt = $conn->prepare("SELECT * FROM likes WHERE post_id = ? AND user_id = ?");
        $like_stmt->bind_param("ii", $post_id, $user_id);
        $like_stmt->execute();
        $liked = $like_stmt->get_result()->num_rows > 0;
    }

    $likes_count_stmt = $conn->prepare("SELECT COUNT(*) AS likes_count FROM likes WHERE post_id = ?");
    $likes_count_stmt->bind_param("i", $post_id);
    $likes_count_stmt->execute();
    $likes_count = $likes_count_stmt->get_result()->fetch_assoc()['likes_count'];
?>

<?php include '../includes/header.php'; ?>

<main class="container my-5">
    <h2><?php echo htmlspecialchars($post['title']); ?></h2>
    <p>by <?php echo htmlspecialchars($post['author']); ?> on <?php echo htmlspecialchars($post['created_at']); ?></p>
    <div>
        <p><?php echo nl2br($post['content']); ?></p>
    </div>

    <div>
        <form action="../includes/like_post.php" method="POST">
            <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
            <?php if ($liked): ?>
                <button type="submit" class="btn btn-danger" name="unlike">Unlike</button>
            <?php else: ?>
                <button type="submit" class="btn btn-primary" name="like">Like</button>
            <?php endif; ?>
            <span><?php echo $likes_count; ?> Likes</span>
        </form>
    </div>

    <hr>


    <h3>Comments</h3>
    <?php if (isset($_SESSION['user'])): ?>
        <form action="../includes/add_comment.php" method="POST">
            <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
            <textarea name="comment" class="form-control mb-2" rows="3" placeholder="Add a comment..." required></textarea>
            <button type="submit" class="btn btn-success">Submit</button>
        </form>
    <?php else: ?>
        <p class="text-danger">You must be logged in to like or comment.</p>
    <?php endif; ?>

    <ul class="list-group mt-3">
        <?php while ($comment = $comments->fetch_assoc()): ?>
            <li class="list-group-item">
                <strong><?php echo htmlspecialchars($comment['username']); ?>:</strong>
                <p><?php echo htmlspecialchars($comment['comment']); ?></p>
                <small><?php echo $comment['created_at']; ?></small>
            </li>
        <?php endwhile; ?>
    </ul>

</main>
<br>
<?php include '../includes/footer.php'; ?>