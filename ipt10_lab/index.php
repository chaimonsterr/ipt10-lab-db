<?php
require_once 'db_connect.php';

// TODO(4): Select query ordered by enrolment_date DESC, then id DESC
$sql = "SELECT id, first_name, last_name, email, enrolment_date 
        FROM students 
        ORDER BY enrolment_date DESC, id DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Records (mysqli)</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container py-4">
    <h2>All Student Records</h2>
    <a href="create.php" class="btn btn-primary mb-3">Add New Student</a>
    <table class="table table-bordered">
        <thead>
            <tr><th>ID</th><th>Name</th><th>Email</th><th>Enrolled</th><th>Actions</th></tr>
        </thead>
        <tbody>
            <?php // TODO(5): Loop through results ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['enrolment_date']) ?></td>
                <td>
                    <a href="view.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info">View</a>
                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php
    // TODO(6): Release resources
    mysqli_free_result($result);
    $conn->close();
    ?>
</body>
</html>