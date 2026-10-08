<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gmail = $_POST['gmail'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if Gmail already exists
    $check = $conn->prepare("SELECT gmail FROM users WHERE gmail = ?");
    $check->bind_param("s", $gmail);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $error = "Gmail already registered.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (gmail, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $gmail, $password);

        if ($stmt->execute()) {
            $success = "Signup successful. <a href='login.php'>Login here</a>";
        } else {
            $error = "Error: " . $stmt->error;
        }

        $stmt->close();
    }
    $check->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Signup</title>
    <link rel="stylesheet" href="css/auth.css">
</head>
<body>
    <div class="auth-container">
        <h2>Signup</h2>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
        <form method="POST">
            <input type="email" name="gmail" placeholder="Enter your Gmail" required>
            <input type="password" name="password" placeholder="Enter your Password" required>
            <button type="submit">Sign Up</button>
        </form>
        <a href="login.php">Already have an account? Login</a>
    </div>
</body>
</html>
