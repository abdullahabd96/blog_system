<?php
    include 'includes/db.php';
    $posts = $conn->query("SELECT p.*, u.username AS author FROM posts p JOIN users u ON p.author_id = u.id ORDER BY p.created_at DESC");
?>
<?php include 'includes/header.php'; ?>
<main class="container my-5">
    <h2>Latest Posts</h2>
    <?php while ($post = $posts -> fetch_assoc()): ?>
        <div class="card mb-3">
            <div class="card-body">
                <h3 class="card-title"><?php echo $post['title']; ?></h3>
                <p class="card-text">by <?php echo $post['author']; ?> on <?php echo $post['created_at']; ?></p>
                <a href="posts/view_post.php?id=<?php echo $post['id']; ?>" class="btn btn-primary">Read More</a>
            </div>
        </div>
    <?php endwhile; ?>    
</main>
<br>
<?php include 'includes/footer.php'; ?>