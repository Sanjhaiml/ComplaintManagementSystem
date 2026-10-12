<?php
include "../db.php";
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit;
}

$id = intval($_GET["id"] ?? 0);
$stmt = $conn->prepare("SELECT c.*, u.name, u.email FROM complaints c JOIN users u ON c.user_id=u.id WHERE c.id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) die("Complaint not found.");

$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $status = $_POST["status"];
    $response = trim($_POST["admin_response"]);

    $update = $conn->prepare("UPDATE complaints SET status=?, admin_response=? WHERE id=?");
    $update->bind_param("ssi", $status, $response, $id);
    if ($update->execute()) {
        $message = "Complaint updated successfully.";
        $row["status"] = $status;
        $row["admin_response"] = $response;
    } else {
        $message = "Update failed.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Complaint</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<header class="topbar">
    <img src="../assets/kcet_logo.png" alt="KCET Logo">
</header>
<div class="form-container wide">
    <h2>Manage Complaint</h2>
    <?php if ($message): ?><div class="message"><?= htmlspecialchars($message) ?></div><?php endif; ?>

    <p><strong>Complaint ID:</strong> <?= htmlspecialchars($row["complaint_id"]) ?></p>
    <p><strong>User:</strong> <?= htmlspecialchars($row["name"]) ?> (<?= htmlspecialchars($row["email"]) ?>)</p>
    <p><strong>Title:</strong> <?= htmlspecialchars($row["title"]) ?></p>
    <p><strong>Category:</strong> <?= htmlspecialchars($row["category"]) ?></p>
    <p><strong>Description:</strong><br><?= nl2br(htmlspecialchars($row["description"])) ?></p>

    <form method="post">
        <label>Status</label>
        <select name="status">
            <?php foreach (["Pending","In Progress","Resolved","Rejected"] as $s): ?>
                <option <?= $row["status"] === $s ? "selected" : "" ?>><?= $s ?></option>
            <?php endforeach; ?>
        </select>

        <label>Admin Response</label>
        <textarea name="admin_response" rows="6"><?= htmlspecialchars($row["admin_response"] ?? "") ?></textarea>

        <button type="submit" class="btn">Update Complaint</button>
        <a href="admin_dashboard.php" class="btn secondary">Back</a>
    </form>
</div>
</body>
</html>
