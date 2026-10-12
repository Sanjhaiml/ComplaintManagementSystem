<?php
include "db.php";
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$id = intval($_GET["id"] ?? 0);
$stmt = $conn->prepare("SELECT * FROM complaints WHERE id=? AND user_id=?");
$stmt->bind_param("ii", $id, $_SESSION["user_id"]);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    die("Complaint not found.");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Complaint Details</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="topbar">
    <img src="assets/kcet_logo.png" alt="KCET Logo">
</header>
<div class="container">
    <div class="card">
        <h2>Complaint Details</h2>
        <p><strong>Complaint ID:</strong> <?= htmlspecialchars($row["complaint_id"]) ?></p>
        <p><strong>Title:</strong> <?= htmlspecialchars($row["title"]) ?></p>
        <p><strong>Category:</strong> <?= htmlspecialchars($row["category"]) ?></p>
        <p><strong>Description:</strong><br><?= nl2br(htmlspecialchars($row["description"])) ?></p>
        <p><strong>Date:</strong> <?= htmlspecialchars($row["complaint_date"]) ?></p>
        <p><strong>Status:</strong> <span class="status <?= strtolower(str_replace(' ', '-', $row["status"])) ?>"><?= htmlspecialchars($row["status"]) ?></span></p>
        <p><strong>Admin Response:</strong><br><?= $row["admin_response"] ? nl2br(htmlspecialchars($row["admin_response"])) : "No response yet." ?></p>
        <a href="dashboard.php" class="btn secondary">Back</a>
    </div>
</div>
</body>
</html>
