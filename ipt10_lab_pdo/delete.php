<?php
require_once 'config.php';

$id = trim($_GET['id'] ?? ($_POST['id'] ?? ''));
if ($id === '') { die('Invalid student ID'); }

$s = $pdo->prepare("SELECT first_name, last_name, email FROM students WHERE id = ?");
$s->execute([$id]);
$r = $s->fetch();

if (!$r) {
    echo '<p class="text-center py-5">Student record not found.</p>';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $d = $pdo->prepare("DELETE FROM students WHERE id = ?");
    $d->execute([$id]);

    if ($d->rowCount() > 0) {
        header("Location: index.php");
        exit;
    } else {
        echo '<p class="text-center py-5">Failed to delete student record.</p>';
    }
} else {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Confirm Deletion</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
        <style>
            body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
            .card-custom { border: none; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        </style>
    </head>
    <body>

        <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-4">
            <div class="container">
                <a class="navbar-brand fw-bold" href="index.php"><i class="bi bi-mortarboard-fill me-2"></i>Student Management System</a>
            </div>
        </nav>

        <div class="container py-4" style="max-width: 550px;">
            <div class="card card-custom text-center p-4">
                <div class="text-danger mb-3">
                    <i class="bi bi-exclamation-triangle-fill display-3"></i>
                </div>
                <h4 class="fw-bold mb-2">Delete Student Record?</h4>
                <p class="text-muted mb-4">Are you sure you want to permanently remove <strong><?= htmlspecialchars($r['first_name'] . ' ' . $r['last_name']) ?></strong> (<?= htmlspecialchars($r['email']) ?>)? This action cannot be undone.</p>
                
                <form method="POST">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
                    <div class="d-flex justify-content-center gap-2">
                        <a href="index.php" class="btn btn-light px-4">Cancel</a>
                        <button type="submit" class="btn btn-danger px-4"><i class="bi bi-trash-fill me-1"></i> Yes, Delete</button>
                    </div>
                </form>
            </div>
        </div>

    </body>
    </html>
    <?php
}
?>