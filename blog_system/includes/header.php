<?php
include_once __DIR__.'/../includes/config.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog System</title>
    <link rel="stylesheet" href="<?php echo $base_url; ?>assets/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-dark text-white text-center py -3">
        <h1>My Blog System</h1>
        <nav>
            <a href="<?php echo $base_url; ?>index.php" class="text-white mx-2">Home</a>
            <?php if(isset($_SESSION['user'])): ?>
                <a href="<?php echo $base_url; ?>admin/dashboard.php" class="text-white mx-2">Dashboard</a>
                <?php if($_SESSION['user']['role'] !== 'admin'): ?>
                    <a href="<?php echo $base_url; ?>admin/manage_users.php" class="text-white mx-2" hidden>Admin</a>
                <?php elseif($_SESSION['user']['role'] === 'admin'): ?>
                    <a href="<?php echo $base_url; ?>admin/admin.php" class="text-white mx-2">Admin</a>
                <?php endif; ?>
                <a href="<?php echo $base_url; ?>admin/logout.php" class="text-white mx-2">Logout</a>
            <?php else: ?>
                <a href="<?php echo $base_url; ?>admin/login.php" class="text-white mx-2">Login</a>
                <a href="<?php echo $base_url; ?>signup.php" class="text-white mx-2">Sign Up</a>
            <?php endif; ?>
        </nav>
    </header>
</body>
</html>