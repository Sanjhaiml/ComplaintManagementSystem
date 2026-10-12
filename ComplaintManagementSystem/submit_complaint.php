<?php
include "db.php";
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST["title"]);
    $category = trim($_POST["category"]);
    $description = trim($_POST["description"]);

    if ($title === "" || $category === "" || $description === "") {
        $message = "Please fill all fields.";
    } else {
        $complaint_id = "CMP" . date("YmdHis") . rand(10,99);
        $stmt = $conn->prepare("INSERT INTO complaints (complaint_id,user_id,title,category,description) VALUES (?,?,?,?,?)");
        $stmt->bind_param("sisss", $complaint_id, $_SESSION["user_id"], $title, $category, $description);
        if ($stmt->execute()) {
            header("Location: view_complaint.php?id=" . $stmt->insert_id);
            exit;
        }
        $message = "Complaint submission failed.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Submit Complaint</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="topbar">
    <img src="assets/kcet_logo.png" alt="KCET Logo">
</header>
<div class="form-container wide">
    <h2>Submit a Complaint</h2>
    <?php if ($message): ?><div class="error"><?= htmlspecialchars($message) ?></div><?php endif; ?>
    <form method="post" onsubmit="return validateComplaint()">
        <label>Complaint Title *</label>
        <input type="text" name="title" id="title" required>

        <label>Category *</label>
        <select name="category" required>
            <option value="">Select Category</option>
            <option>Hostel</option>
            <option>Transport</option>
            <option>Academic</option>
            <option>Infrastructure</option>
            <option>Library</option>
            <option>Internet</option>
            <option>Other</option>
        </select>

        <label>Description *</label>
        <textarea name="description" id="description" rows="7" required></textarea>

        <button type="submit" class="btn">Submit Complaint</button>
        <a href="dashboard.php" class="btn secondary">Cancel</a>
    </form>
</div>
<script src="js/script.js"></script>
</body>
</html>
