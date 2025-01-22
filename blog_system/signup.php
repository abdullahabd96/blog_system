<?php
    include 'includes/db.php';

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $conn->real_escape_string($_POST['username']);
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        
        if($stmt->num_rows > 0) {
            $error = "Username already exists. Please choose another.";
        } else {
            $usr = $conn->query("SELECT COUNT(*) AS user_count FROM users");
            $user_count = $usr->fetch_assoc()['user_count'];
            if($user_count > 0){
                $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
                $stmt->bind_param("ss", $username, $password);
                if($stmt->execute()) {
                    $success = "Signup successful! You can now <a href='admin/login.php'>login</a>.";
                } else {
                    $error = "An error occured. Please try again.";
                }
            } else {
                $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'admin')");
                $stmt->bind_param("ss", $username, $password);
                if($stmt->execute()) {
                    $success = "Signup successful as ADMIN! You can now <a href='admin/login.php'>Login as ADMIN</a>.";
                } else {
                    $error = "An error occured. Please try again.";
                }
            }
        }
        $stmt->close();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center">Sign Up</h1>
        <?php if(isset($error)): ?>
            <p class="text-danger text-center"><?php echo $error; ?></p>
        <?php elseif (isset($success)): ?>
            <p class="text-success text-center"><?php echo $success; ?></p>
        <?php endif; ?>
        <form action="signup.php" method="POST" class="w-50 mx-auto">
            <input type="text" name="username" class="form-control mb-3" placeholder="Username" required>
            <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
            <button type="submit" class="btn btn-primary w-100">Sign Up</button>
        </form>
        <p class="text-center mt-3">Already have an account? <a href="admin/login.php">Login here</a>, or</p>
        <p class="text-center">visit as<a href="index.php"> guest</a>
    </div>
</body>
</html>

