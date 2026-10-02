<?php
require_once 'db_connect.php';

$id = trim($_GET['id'] ?? '');
if ($id === '') { die('Invalid student ID'); }

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // TODO(15): Collect inputs and run validations
    $first_name     = trim($_POST['first_name'] ?? '');
    $middle_name    = trim($_POST['middle_name'] ?? '');
    $last_name      = trim($_POST['last_name'] ?? '');
    $email          = trim($_POST['email'] ?? '');
    $birthday       = trim($_POST['birthday'] ?? '');
    $sex            = trim($_POST['sex'] ?? '');
    $student_number = trim($_POST['student_number'] ?? '');
    $program        = trim($_POST['program'] ?? '');
    $enrolment_date = trim($_POST['enrolment_date'] ?? '');

    if (empty($first_name) || !preg_match('/^[A-Za-z\s]+$/', $first_name)) { $errors['first_name'] = 'Invalid first name.'; }
    if (empty($last_name) || !preg_match('/^[A-Za-z\s]+$/', $last_name)) { $errors['last_name'] = 'Invalid last name.'; }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors['email'] = 'Invalid email.'; }

    if (empty($errors)) {
        // TODO(16): UPDATE query ending with WHERE id = ?
        $stmt = $conn->prepare("UPDATE students SET 
            first_name = ?, middle_name = ?, last_name = ?, birthday = ?, sex = ?, 
            email = ?, student_number = ?, program = ?, enrolment_date = ? 
            WHERE id = ?");
        $stmt->bind_param('ssssssssss', $first_name, $middle_name, $last_name, $birthday, $sex, $email, $student_number, $program, $enrolment_date, $id);
        $stmt->execute();

        // TODO(17): Check affected rows
        // Note: affected_rows can be 0 if the query executed successfully but no fields were modified!
        header("Location: index.php");
        exit;
    }
} else {
    // Pre-fill existing data
    $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->bind_param('s', $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    if (!$row) { die('Student not found'); }
    
    $first_name     = $row['first_name'];
    $middle_name    = $row['middle_name'];
    $last_name      = $row['last_name'];
    $email          = $row['email'];
    $birthday       = $row['birthday'];
    $sex            = $row['sex'];
    $student_number = $row['student_number'];
    $program        = $row['program'];
    $enrolment_date = $row['enrolment_date'];
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container py-4">
    <h2>Edit Student</h2>
    <form method="POST">
        <div class="mb-3">
            <label>First Name</label>
            <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($first_name) ?>">
        </div>
        <div class="mb-3">
            <label>Middle Name</label>
            <input type="text" name="middle_name" class="form-control" value="<?= htmlspecialchars($middle_name) ?>">
        </div>
        <div class="mb-3">
            <label>Last Name</label>
            <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($last_name) ?>">
        </div>
        <div class="mb-3">
            <label>Birthday</label>
            <input type="date" name="birthday" class="form-control" value="<?= htmlspecialchars($birthday) ?>">
        </div>
        <div class="mb-3">
            <label>Sex</label>
            <select name="sex" class="form-select">
                <option value="Male" <?= $sex === 'Male' ? 'selected' : '' ?>>Male</option>
                <option value="Female" <?= $sex === 'Female' ? 'selected' : '' ?>>Female</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>">
        </div>
        <div class="mb-3">
            <label>Student Number</label>
            <input type="text" name="student_number" class="form-control" value="<?= htmlspecialchars($student_number) ?>">
        </div>
        <div class="mb-3">
            <label>Program</label>
            <input type="text" name="program" class="form-control" value="<?= htmlspecialchars($program) ?>">
        </div>
        <div class="mb-3">
            <label>Enrolment Date</label>
            <input type="date" name="enrolment_date" class="form-control" value="<?= htmlspecialchars($enrolment_date) ?>">
        </div>
        <button type="submit" class="btn btn-warning">Update Record</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
    <?php $conn->close(); ?>
</body>
</html>