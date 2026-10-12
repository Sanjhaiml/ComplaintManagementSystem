<?php
include "db.php";
session_start();
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $password = $_POST["password"];

    if ($name === "" || $email === "" || $password === "") {
        $message = "Please fill all required fields.";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (name,email,phone,password) VALUES (?,?,?,?)");
        $stmt->bind_param("ssss", $name, $email, $phone, $hashed);

        if ($stmt->execute()) {
            $message = "Registration successful. You can now login.";
        } else {
            $message = "Email already exists or registration failed.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="topbar">
    <img src="assets/kcet_logo.png" alt="KCET Logo">
</header>

<div class="form-container">
    <h2>Create Account</h2>
    <?php if ($message): ?><div class="message"><?= htmlspecialchars($message) ?></div><?php endif; ?>
    <form method="post" onsubmit="return validateRegister()">
        <label>Name *</label>
        <input type="text" name="name" id="name" required>

        <label>Email *</label>
        <input type="email" name="email" id="email" required>

        <label>Phone</label>
        <input type="text" name="phone">

        <label>Password *</label>
        <input type="password" name="password" id="password" required>

        <button type="submit" class="btn">Register</button>
    </form>
    <p>Already registered? <a href="login.php">Login</a></p>
</div>
<script src="js/script.js"></script>
</body>
</html>
