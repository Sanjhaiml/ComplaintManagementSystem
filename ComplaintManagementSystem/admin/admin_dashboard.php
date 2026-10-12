<?php
include "../db.php";
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit;
}

$total = $conn->query("SELECT COUNT(*) c FROM complaints")->fetch_assoc()["c"];
$pending = $conn->query("SELECT COUNT(*) c FROM complaints WHERE status='Pending'")->fetch_assoc()["c"];
$progress = $conn->query("SELECT COUNT(*) c FROM complaints WHERE status='In Progress'")->fetch_assoc()["c"];
$resolved = $conn->query("SELECT COUNT(*) c FROM complaints WHERE status='Resolved'")->fetch_assoc()["c"];

$result = $conn->query("SELECT c.*, u.name, u.email FROM complaints c JOIN users u ON c.user_id=u.id ORDER BY c.id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<header class="topbar">
    <img src="../assets/kcet_logo.png" alt="KCET Logo">
    <div class="nav">
        <span>Admin: <?= htmlspecialchars($_SESSION["admin_username"]) ?></span>
        <a href="logout.php">Logout</a>
    </div>
</header>

<div class="container">
    <h1>Admin Dashboard</h1>
    <div class="stats">
        <div class="stat"><h3>Total</h3><strong><?= $total ?></strong></div>
        <div class="stat"><h3>Pending</h3><strong><?= $pending ?></strong></div>
        <div class="stat"><h3>In Progress</h3><strong><?= $progress ?></strong></div>
        <div class="stat"><h3>Resolved</h3><strong><?= $resolved ?></strong></div>
    </div>

    <div class="card">
        <h2>All Complaints</h2>
        <div class="table-wrap">
        <table>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Title</th>
                <th>Category</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row["complaint_id"]) ?></td>
                <td><?= htmlspecialchars($row["name"]) ?></td>
                <td><?= htmlspecialchars($row["title"]) ?></td>
                <td><?= htmlspecialchars($row["category"]) ?></td>
                <td><span class="status <?= strtolower(str_replace(' ', '-', $row["status"])) ?>"><?= htmlspecialchars($row["status"]) ?></span></td>
                <td><a href="update_complaint.php?id=<?= $row["id"] ?>">Manage</a></td>
            </tr>
            <?php endwhile; ?>
        </table>
        </div>
    </div>
</div>
</body>
</html>
