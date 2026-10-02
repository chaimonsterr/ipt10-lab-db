<?php
require_once 'db_connect.php';

$id = trim($_GET['id'] ?? '');
if ($id === '') { die('Invalid student ID'); }

// TODO(7): Prepare SELECT statement
$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
$stmt->bind_param('s', $id);
$stmt->execute();

// TODO(8): Fetch single associative row
$result = $stmt->get_result();
$row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Student Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container py-4">
    <?php if (!$row): ?>
        <p>Student not found.</p>
    <?php else: ?>
        <h2>Student Details</h2>
        <div class="card p-3 mb-3">
            <p><strong>Student ID:</strong> <?= htmlspecialchars($row['id']) ?></p>
            <p><strong>Full Name:</strong> <?= htmlspecialchars($row['first_name'] . ' ' . ($row['middle_name'] ?? '') . ' ' . $row['last_name']) ?></p>
            <p><strong>Birthday:</strong> <?= htmlspecialchars($row['birthday']) ?></p>
            <p><strong>Sex:</strong> <?= htmlspecialchars($row['sex']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($row['email']) ?></p>
            <p><strong>Student Number:</strong> <?= htmlspecialchars($row['student_number']) ?></p>
            <p><strong>Program:</strong> <?= htmlspecialchars($row['program']) ?></p>
            <p><strong>Enrolment Date:</strong> <?= htmlspecialchars($row['enrolment_date']) ?></p>
            <p><strong>Created At:</strong> <?= htmlspecialchars($row['created_at']) ?></p>
        </div>
    <?php endif; ?>
    <a href="index.php" class="btn btn-secondary">Back to List</a>
    <?php
    $stmt->close();
    $conn->close();
    ?>
</body>
</html>