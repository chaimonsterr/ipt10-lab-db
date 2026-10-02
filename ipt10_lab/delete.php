<?php
require_once 'db_connect.php';

$id = trim($_GET['id'] ?? ($_POST['id'] ?? ''));
if ($id === '') { die('Invalid student ID'); }

// Step 1: Look up student name for confirmation screen
// TODO(18): Prepared SELECT by id
$s = $conn->prepare("SELECT first_name, last_name FROM students WHERE id = ?");
$s->bind_param('s', $id);
$s->execute();
$r = $s->get_result()->fetch_assoc();
$s->close();

if (!$r) {
    echo '<p>Student not found.</p>';
    $conn->close();
    exit;
}

// Step 2: Delete upon POST confirmation
// TODO(19): Check request method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $d = $conn->prepare("DELETE FROM students WHERE id = ?");
    $d->bind_param('s', $id);
    $d->execute();

    if ($d->affected_rows > 0) {
        header("Location: index.php");
        exit;
    } else {
        echo '<p>Failed to delete student.</p>';
    }
    $d->close();
    $conn->close();
} else {
    // TODO(20): Render confirmation screen
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Delete Confirmation</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    </head>
    <body class="container py-4">
        <h2>Confirm Deletion</h2>
        <p>Are you sure you want to delete student <strong><?= htmlspecialchars($r['first_name'] . ' ' . $r['last_name']) ?></strong>?</p>
        <form method="POST">
            <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
            <button type="submit" class="btn btn-danger">Yes, Delete</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </body>
    </html>
    <?php
    $conn->close();
}
?>