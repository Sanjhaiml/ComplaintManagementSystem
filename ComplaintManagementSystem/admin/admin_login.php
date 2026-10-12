<?php
include "../db.php";
session_start();
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT id FROM admins WHERE username=? AND password=?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $_SESSION["admin_id"] = $result->fetch_assoc()["id"];
        $_SESSION["admin_username"] = $username;
        header("Location: admin_dashboard.php");
        exit;
    }
    $message = "Invalid admin username or password.";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<header class="topbar">
    <img src="../assets/kcet_logo.png" alt="KCET Logo">
</header>
<div class="form-container">
    <h2>Admin Login</h2>
    <?php if ($message): ?><div class="error"><?= htmlspecialchars($message) ?></div><?php endif; ?>
    <form method="post">
        <label>Username</label>
        <input type="text" name="username" required>
        <label>Password</label>
        <input type="password" name="password" required>
        <button type="submit" class="btn dark">Login</button>
    </form>
    <p>Default: <strong>admin</strong> / <strong>admin123</strong></p>
    <p><a href="../index.php">Back to Home</a></p>
</div>
</body>
</html>
