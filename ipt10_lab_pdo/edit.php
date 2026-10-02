<?php
require_once 'config.php';

$id = trim($_GET['id'] ?? '');
if ($id === '') { die('Invalid student ID'); }

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors['email'] = 'Invalid email address.'; }

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE students SET 
            first_name = ?, middle_name = ?, last_name = ?, birthday = ?, sex = ?, 
            email = ?, student_number = ?, program = ?, enrolment_date = ? 
            WHERE id = ?");
        
        $data = [$first_name, $middle_name, $last_name, $birthday, $sex, $email, $student_number, $program, $enrolment_date, $id];
        $stmt->execute($data);

        header("Location: index.php");
        exit;
    }
} else {
    $stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if (!$row) { die('Student record not found'); }
    
    $first_name     = $row['first_name'];
    $middle_name    = $row['middle_name'];
    $last_name      = $row['last_name'];
    $email          = $row['email'];
    $birthday       = $row['birthday'];
    $sex            = $row['sex'];
    $student_number = $row['student_number'];
    $program        = $row['program'];
    $enrolment_date = $row['enrolment_date'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student Record</title>
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

    <div class="container mb-5" style="max-width: 800px;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0">Edit Student Record</h4>
            <a href="index.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back to List</a>
        </div>

        <div class="card card-custom p-4">
            <form method="POST">
                <h6 class="text-warning text-dark fw-bold mb-3"><i class="bi bi-pencil-square me-2"></i>Update Student Details</h6>
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label">First Name</label>
                        <input type="text" name="first_name" class="form-control <?= isset($errors['first_name']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($first_name) ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Middle Name</label>
                        <input type="text" name="middle_name" class="form-control" value="<?= htmlspecialchars($middle_name) ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="last_name" class="form-control <?= isset($errors['last_name']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($last_name) ?>">
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Birthday</label>
                        <input type="date" name="birthday" class="form-control" value="<?= htmlspecialchars($birthday) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Sex</label>
                        <select name="sex" class="form-select">
                            <option value="Male" <?= $sex === 'Male' ? 'selected' : '' ?>>Male</option>
                            <option value="Female" <?= $sex === 'Female' ? 'selected' : '' ?>>Female</option>
                        </select>
                    </div>
                </div>

                <hr class="my-4">

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($email) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Student Number</label>
                        <input type="text" name="student_number" class="form-control" value="<?= htmlspecialchars($student_number) ?>">
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-8">
                        <label class="form-label">Academic Program</label>
                        <input type="text" name="program" class="form-control" value="<?= htmlspecialchars($program) ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Enrolment Date</label>
                        <input type="date" name="enrolment_date" class="form-control" value="<?= htmlspecialchars($enrolment_date) ?>">
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="index.php" class="btn btn-light px-4">Cancel</a>
                    <button type="submit" class="btn btn-warning px-4"><i class="bi bi-check2-circle me-1"></i> Update Record</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>