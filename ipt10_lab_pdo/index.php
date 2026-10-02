<?php
require_once 'config.php';

$sql = "SELECT id, first_name, last_name, email, enrolment_date 
        FROM students 
        ORDER BY enrolment_date DESC, id DESC";
$stmt = $pdo->query($sql);
$rows = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .navbar-brand { font-weight: 700; letter-spacing: 0.5px; }
        .card-custom { border: none; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .table-hover tbody tr:hover { background-color: #f8f9fa; }
        .badge-id { font-family: monospace; font-size: 0.85rem; background-color: #eef2f7; color: #495057; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand" href="index.php"><i class="bi bi-mortarboard-fill me-2"></i>Student Management System</a>
        </div>
    </nav>

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Student Directory</h3>
                <p class="text-muted small mb-0">Manage and review student enrolment records</p>
            </div>
            <a href="create.php" class="btn btn-primary px-3 shadow-sm">
                <i class="bi bi-person-plus-fill me-1"></i> Add New Student
            </a>
        </div>

        <div class="card card-custom p-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Student ID</th>
                            <th>Full Name</th>
                            <th>Email Address</th>
                            <th>Enrolment Date</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($rows)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No student records found.</td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><span class="badge badge-id px-2 py-1"><?= htmlspecialchars(substr($row['id'], 0, 8)) ?>...</span></td>
                            <td class="fw-semibold text-dark"><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                            <td><a href="mailto:<?= htmlspecialchars($row['email']) ?>" class="text-decoration-none"><?= htmlspecialchars($row['email']) ?></a></td>
                            <td><i class="bi bi-calendar3 me-1 text-muted"></i><?= htmlspecialchars($row['enrolment_date']) ?></td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="view.php?id=<?= $row['id'] ?>" class="btn btn-outline-info" title="View Details"><i class="bi bi-eye"></i></a>
                                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-outline-warning" title="Edit Record"><i class="bi bi-pencil"></i></a>
                                    <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-outline-danger" title="Delete Record"><i class="bi bi-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>