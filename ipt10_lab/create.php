<?php
require_once 'db_connect.php';

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

    // TODO(10): First & Last Name validation
    if (empty($first_name) || strlen($first_name) < 2 || strlen($first_name) > 100 || !preg_match('/^[A-Za-z\s]+$/', $first_name)) {
        $errors['first_name'] = 'First name is required (2-100 chars, letters/spaces only).';
    }
    if (empty($last_name) || strlen($last_name) < 2 || strlen($last_name) > 100 || !preg_match('/^[A-Za-z\s]+$/', $last_name)) {
        $errors['last_name'] = 'Last name is required (2-100 chars, letters/spaces only).';
    }

    // TODO(11): Email & Birthday validation
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'A valid email address is required.';
    }
    if (empty($birthday) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $birthday)) {
        $errors['birthday'] = 'Birthday is required in YYYY-MM-DD format.';
    }

    // TODO(12): Sex ENUM validation
    if (!in_array($sex, ['Male', 'Female'], true)) {
        $errors['sex'] = 'Sex must be either Male or Female.';
    }

    if (empty($enrolment_date) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $enrolment_date)) {
        $errors['enrolment_date'] = 'Enrolment date is required in YYYY-MM-DD format.';
    }

    if (empty($errors)) {
        // TODO(13): INSERT with UUID()
        $stmt = $conn->prepare("INSERT INTO students 
            (id, first_name, middle_name, last_name, birthday, sex, email, student_number, program, enrolment_date) 
            VALUES (UUID(), ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        // TODO(14): Bind 9 string parameters ('sssssssss')
        $stmt->bind_param('sssssssss', $first_name, $middle_name, $last_name, $birthday, $sex, $email, $student_number, $program, $enrolment_date);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $success = "Student created successfully!";
            $first_name = $middle_name = $last_name = $email = $birthday = $sex = $student_number = $program = $enrolment_date = '';
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Student</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container py-4">
    <h2>Add New Student</h2>
    <?php if (!empty($success)): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label>First Name</label>
            <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($first_name) ?>">
            <?php if (isset($errors['first_name'])): ?><span class="text-danger"><?= $errors['first_name'] ?></span><?php endif; ?>
        </div>
        <div class="mb-3">
            <label>Middle Name</label>
            <input type="text" name="middle_name" class="form-control" value="<?= htmlspecialchars($middle_name) ?>">
        </div>
        <div class="mb-3">
            <label>Last Name</label>
            <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($last_name) ?>">
            <?php if (isset($errors['last_name'])): ?><span class="text-danger"><?= $errors['last_name'] ?></span><?php endif; ?>
        </div>
        <div class="mb-3">
            <label>Birthday</label>
            <input type="date" name="birthday" class="form-control" value="<?= htmlspecialchars($birthday) ?>">
            <?php if (isset($errors['birthday'])): ?><span class="text-danger"><?= $errors['birthday'] ?></span><?php endif; ?>
        </div>
        <div class="mb-3">
            <label>Sex</label>
            <select name="sex" class="form-select">
                <option value="">Select...</option>
                <option value="Male" <?= $sex === 'Male' ? 'selected' : '' ?>>Male</option>
                <option value="Female" <?= $sex === 'Female' ? 'selected' : '' ?>>Female</option>
            </select>
            <?php if (isset($errors['sex'])): ?><span class="text-danger"><?= $errors['sex'] ?></span><?php endif; ?>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>">
            <?php if (isset($errors['email'])): ?><span class="text-danger"><?= $errors['email'] ?></span><?php endif; ?>
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
            <?php if (isset($errors['enrolment_date'])): ?><span class="text-danger"><?= $errors['enrolment_date'] ?></span><?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="index.php" class="btn btn-secondary">Back</a>
    </form>
    <?php $conn->close(); ?>
</body>
</html>