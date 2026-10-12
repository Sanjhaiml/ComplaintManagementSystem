<?php
include "db.php";
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION["user_id"];
$stmt = $conn->prepare("SELECT * FROM complaints WHERE user_id=? ORDER BY id DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="topbar">
    <img src="assets/kcet_logo.png" alt="KCET Logo">
    <div class="nav">
        <span>Welcome, <?= htmlspecialchars($_SESSION["user_name"]) ?></span>
        <a href="logout.php">Logout</a>
    </div>
</header>

<div class="container">
    <div class="dashboard-head">
        <div>
            <h1>User Dashboard</h1>
            <p>Manage and track your complaints.</p>
        </div>
        <a href="submit_complaint.php" class="btn">+ Submit Complaint</a>
    </div>

    <div class="card">
        <h2>My Complaints</h2>
        <?php if ($result->num_rows === 0): ?>
            <p>No complaints submitted yet.</p>
        <?php else: ?>
        <div class="table-wrap">
        <table>
            <tr>
                <th>Complaint ID</th>
                <th>Title</th>
                <th>Category</th>
                <th>Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row["complaint_id"]) ?></td>
                <td><?= htmlspecialchars($row["title"]) ?></td>
                <td><?= htmlspecialchars($row["category"]) ?></td>
                <td><?= htmlspecialchars(date("d-m-Y", strtotime($row["complaint_date"]))) ?></td>
                <td><span class="status <?= strtolower(str_replace(' ', '-', $row["status"])) ?>"><?= htmlspecialchars($row["status"]) ?></span></td>
                <td><a href="view_complaint.php?id=<?= $row["id"] ?>">View</a></td>
            </tr>
            <?php endwhile; ?>
        </table>
        </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
