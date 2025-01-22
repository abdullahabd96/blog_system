<?php
    session_start();
    include '../includes/db.php';
    session_abort();
    include '../includes/header.php';

    if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        echo "<p class-'text-danger text-center my-3'>Access Denied! Admins only.</p>";
        exit;
    }

    $action = isset($_GET['action']) ? $_GET['action'] : '';
?>

<div class="container my-5">
    <h2>Admin Panel</h2>
    <p>Welcome, <strong><?php echo htmlspecialchars($_SESSION['user']['username']); ?></strong>!</p>

    <nav>
        <a href="?action=manage_users" class="btn btn-primary my-2">Manage Users</a>
        <a href="?action=manage_posts" class="btn btn-secondary my-2">Manage Posts</a>
        <a href="?action=view_dashboard" class="btn btn-success my-2">Dashboard</a>        
    </nav>

    <hr>

    <?php if($action === 'manage_users'): ?>
        <h3>Manage Users</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $result = $conn->query("SELECT id, username, role FROM users");
                    while($user = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['role']); ?></td>
                        <td>
                            <?php if($user['role'] !== 'admin'): ?>
                                <a href="../admin/ad_delete_user.php?id=<?php echo $user['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                                <a href="../admin/ad_change_role.php?id=<?php echo $user['id']; ?>" class="btn btn-warning btn-sm">Change Role</a>
                            <?php else: ?>
                                <span class="text-muted">Admin</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php elseif($action === 'manage_posts'): ?>
        <h3>Manage Posts</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $result = $conn->query("SELECT p.id, p.title, u.username AS author, p.created_at FROM posts p JOIN users u ON p.author_id = u.id");
                    while($post = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $post['id']; ?></td>
                            <td><?php echo htmlspecialchars($post['title']); ?></td>
                            <td><?php echo htmlspecialchars($post['author']); ?></td>
                            <td><?php echo htmlspecialchars($post['created_at']); ?></td>
                            <td>
                                <a href="../admin/ad_delete_post.php?id=<?php echo $post['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                                <a href="../admin/ad_edit_post.php?id=<?php echo $post['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
            </tbody>
        </table>
    <?php elseif($action === 'view_dashboard'): ?>
        <h3>Admin Dashboard</h3>
        <p>Here you can view an overview of the system.</p>
        <ul>
            <li>Total users:
                <?php
                    $user_count = $conn->query("SELECT COUNT(*) AS count FROM users")->fetch_assoc()['count'];
                    echo $user_count;
                ?>
            </li>
            <li>Total Posts:
                <?php
                    $post_count = $conn->query("SELECT COUNT(*) AS count FROM posts")->fetch_assoc()['count'];
                    echo $post_count;
                ?>
            </li>
        </ul>
    <?php else: ?>
        <h3>Welcome to the Admin Panel</h3>
        <p>Select an action from the options above.</p>
    <?php endif; ?>
</div>
<br>
<?php include '../includes/footer.php'; ?>