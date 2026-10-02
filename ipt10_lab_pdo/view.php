<?php
require_once 'config.php';

$id = trim($_GET['id'] ?? '');
if ($id === '') { die('Invalid student ID'); }

$stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$id]);
$row = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .card-custom { border: none; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .profile-avatar { width: 80px; height: 80px; background-color: #e3f2fd; color: #0d6efd; border-radius: 50%; font-size: 2rem; display: flex; align-items: center; justify-content: center; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php"><i class="bi bi-mortarboard-fill me-2"></i>Student Management System</a>
        </div>
    </nav>

    <div class="container mb-5" style="max-width: 750px;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0">Student Profile</h4>
            <a href="index.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back to List</a>
        </div>

        <?php if (!$row): ?>
            <div class="alert alert-danger">Student record not found.</div>
        <?php else: ?>
            <div class="card card-custom p-4 mb-3">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="profile-avatar">
                        <i class="bi bi-person"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0"><?= htmlspecialchars($row['first_name'] . ' ' . ($row['middle_name'] ? $row['middle_name'] . ' ' : '') . $row['last_name']) ?></h4>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill"><?= htmlspecialchars($row['program'] ?: 'Program N/A') ?></span>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Student ID (UUID)</p>
                        <p class="fw-semibold text-break font-monospace small bg-light p-2 rounded"><?= htmlspecialchars($row['id']) ?></p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Student Number</p>
                        <p class="fw-semibold"><?= htmlspecialchars($row['student_number'] ?: 'N/A') ?></p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Email Address</p>
                        <p class="fw-semibold"><?= htmlspecialchars($row['email']) ?></p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Gender / Sex</p>
                        <p class="fw-semibold"><?= htmlspecialchars($row['sex']) ?></p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Date of Birth</p>
                        <p class="fw-semibold"><?= htmlspecialchars($row['birthday']) ?></p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Enrolment Date</p>
                        <p class="fw-semibold"><?= htmlspecialchars($row['enrolment_date']) ?></p>
                    </div>
                </div>

                <hr class="my-3">
                <div class="d-flex justify-content-between text-muted small">
                    <span>Created: <?= htmlspecialchars($row['created_at']) ?></span>
                    <span>Last Updated: <?= htmlspecialchars($row['updated_at']) ?></span>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning px-4"><i class="bi bi-pencil me-1"></i> Edit Profile</a>
                <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-outline-danger px-4"><i class="bi bi-trash me-1"></i> Delete</a>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>