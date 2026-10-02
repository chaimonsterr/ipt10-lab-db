<?php
require_once 'config.php';

$errors = [];
$first_name = $middle_name = $last_name = $email = $birthday = $sex = $student_number = $program = $enrolment_date = '';

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

    if (empty($first_name) || strlen($first_name) < 2 || strlen($first_name) > 100 || !preg_match('/^[A-Za-z\s]+$/', $first_name)) {
        $errors['first_name'] = 'First name is required (letters and spaces only).';
    }
    if (empty($last_name) || strlen($last_name) < 2 || strlen($last_name) > 100 || !preg_match('/^[A-Za-z\s]+$/', $last_name)) {
        $errors['last_name'] = 'Last name is required (letters and spaces only).';
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'A valid email address is required.';
    }
    if (empty($birthday) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $birthday)) {
        $errors['birthday'] = 'Birthday is required.';
    }
    if (!in_array($sex, ['Male', 'Female'], true)) {
        $errors['sex'] = 'Please select a gender.';
    }
    if (empty($enrolment_date) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $enrolment_date)) {
        $errors['enrolment_date'] = 'Enrolment date is required.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO students 
            (id, first_name, middle_name, last_name, birthday, sex, email, student_number, program, enrolment_date) 
            VALUES (UUID(), ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $data = [$first_name, $middle_name, $last_name, $birthday, $sex, $email, $student_number, $program, $enrolment_date];
        $stmt->execute($data);

        if ($stmt->rowCount() > 0) {
            $success = "Student profile created successfully!";
            $first_name = $middle_name = $last_name = $email = $birthday = $sex = $student_number = $program = $enrolment_date = '';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student Record</title>
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
            <h4 class="fw-bold mb-0">Register New Student</h4>
            <a href="index.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back to List</a>
        </div>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i><?= $success ?>
                <a href="index.php" class="alert-link">Return to dashboard</a>.
            </div>
        <?php endif; ?>

        <div class="card card-custom p-4">
            <form method="POST">
                <h6 class="text-primary fw-bold mb-3"><i class="bi bi-person-lines-fill me-2"></i>Personal Information</h6>
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label">First Name *</label>
                        <input type="text" name="first_name" class="form-control <?= isset($errors['first_name']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($first_name) ?>">
                        <div class="invalid-feedback"><?= $errors['first_name'] ?? '' ?></div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Middle Name</label>
                        <input type="text" name="middle_name" class="form-control" value="<?= htmlspecialchars($middle_name) ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Last Name *</label>
                        <input type="text" name="last_name" class="form-control <?= isset($errors['last_name']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($last_name) ?>">
                        <div class="invalid-feedback"><?= $errors['last_name'] ?? '' ?></div>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Birthday *</label>
                        <input type="date" name="birthday" class="form-control <?= isset($errors['birthday']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($birthday) ?>">
                        <div class="invalid-feedback"><?= $errors['birthday'] ?? '' ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Sex *</label>
                        <select name="sex" class="form-select <?= isset($errors['sex']) ? 'is-invalid' : '' ?>">
                            <option value="">Choose...</option>
                            <option value="Male" <?= $sex === 'Male' ? 'selected' : '' ?>>Male</option>
                            <option value="Female" <?= $sex === 'Female' ? 'selected' : '' ?>>Female</option>
                        </select>
                        <div class="invalid-feedback"><?= $errors['sex'] ?? '' ?></div>
                    </div>
                </div>

                <hr class="my-4">
                <h6 class="text-primary fw-bold mb-3"><i class="bi bi-journal-bookmark-fill me-2"></i>Academic & Contact Details</h6>
                
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Email Address *</label>
                        <input type="email" name="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($email) ?>">
                        <div class="invalid-feedback"><?= $errors['email'] ?? '' ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Student Number</label>
                        <input type="text" name="student_number" class="form-control" value="<?= htmlspecialchars($student_number) ?>">
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-8">
                        <label class="form-label">Academic Program</label>
                        <input type="text" name="program" class="form-control" placeholder="e.g. BS in Information Technology" value="<?= htmlspecialchars($program) ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Enrolment Date *</label>
                        <input type="date" name="enrolment_date" class="form-control <?= isset($errors['enrolment_date']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($enrolment_date) ?>">
                        <div class="invalid-feedback"><?= $errors['enrolment_date'] ?? '' ?></div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="index.php" class="btn btn-light px-4">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i> Save Student</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>