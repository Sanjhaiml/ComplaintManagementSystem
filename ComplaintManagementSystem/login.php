<?php
include "db.php";
session_start();
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT id,name,password FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row["password"])) {
            $_SESSION["user_id"] = $row["id"];
            $_SESSION["user_name"] = $row["name"];
            header("Location: dashboard.php");
            exit;
        }
    }
    $message = "Invalid email or password.";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="topbar">
    <img src="assets/kcet_logo.png" alt="KCET Logo">
</header>

<div class="form-container">
    <h2>User Login</h2>
    <?php if ($message): ?><div class="error"><?= htmlspecialchars($message) ?></div><?php endif; ?>
    <form method="post">
        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit" class="btn">Login</button>
    </form>
    <p>New user? <a href="register.php">Create account</a></p>
    <p><a href="index.php">Back to Home</a></p>
</div>
</body>
</html>
