<?php
    include '../includes/db.php';

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $conn->real_escape_string($_POST['username']);
        $password = $conn->real_escape_string($_POST['password']);

        $result = $conn -> query("SELECT * FROM users WHERE username = '$username'");
        if($result -> num_rows > 0) {
            $user = $result->fetch_assoc();
            if(password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['user'] = $user;
                header('Location: dashboard.php');
            } else {
                $error = 'Invalid credentials.';
            }
        } else {
            $error = 'User not found.';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <h1 class="text-center">Login</h1>
    <form action="" method="POST">
        <input type="text" class="form-control mb-3" name="username" id="username" placeholder="Username" required>
        <input type="password" class="form-control mb-3" name="password" id="password" placeholder="Password" required>
        <button type="submit" class="btn btn-primary w-100">Login</button>
        <br>
        <p class="text-center">Not registered yet, then <a href="../signup.php">Sign Up</a> ,or
        <p class="text-center">visit as<a href="../index.php"> guest</a>
    </form>
    <?php
        if(isset($error)) echo "<p> $error </p>";
    ?>
</body>
</html>

