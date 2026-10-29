<?php
declare(strict_types=1);

require_once __DIR__ . '/page_session.php';
requireRole($pdo, 'admin');
require_once __DIR__ . '/audit.php';
require_once __DIR__ . '/db_mysqli.php';

$migration = $mysqli->prepare(
    "ALTER TABLE students ADD COLUMN IF NOT EXISTS status ENUM('active', 'inactive') NOT NULL DEFAULT 'active'"
);
if (!$migration || !$migration->execute()) {
    error_log('Student status migration failed: ' . $mysqli->error);
}
$migration?->close();

$message = '';
$error = '';
$editingStudent = null;

function studentInput(string $key): string
{
    return trim((string) ($_POST[$key] ?? ''));
}

function redirectWithMessage(string $type, string $text): never
{
    header('Location: student_management.php?' . http_build_query([$type => $text]));
    exit();
}

if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    $statement = $mysqli->prepare(
        'SELECT id, fullname, email, phone, course, academic_year, gender, status, created_at
         FROM students WHERE role = "student" ORDER BY fullname'
    );
    $statement->execute();
    $result = $statement->get_result();
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="student-list.csv"');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Name', 'Email', 'Phone', 'Course', 'Academic year', 'Gender', 'Status', 'Created']);
    while ($student = $result->fetch_assoc()) {
        fputcsv($output, $student);
    }
    fclose($output);
    $statement->close();
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = (string) ($_POST['action'] ?? '');
    $studentId = (int) ($_POST['student_id'] ?? 0);

    if ($action === 'save_student') {
        $name = studentInput('fullname');
        $email = strtolower(studentInput('email'));
        $phone = studentInput('phone');
        $course = studentInput('course');
        $academicYear = studentInput('academic_year');
        $gender = studentInput('gender');
        $status = studentInput('status') === 'inactive' ? 'inactive' : 'active';
        $password = (string) ($_POST['password'] ?? '');

        if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            redirectWithMessage('error', 'Enter a valid name and email address.');
        }

        if ($studentId > 0) {
            if ($password !== '') {
                $statement = $mysqli->prepare(
                    'UPDATE students SET fullname = ?, email = ?, phone = ?, course = ?,
                     academic_year = ?, gender = ?, status = ?, password = ? WHERE id = ? AND role = "student"'
                );
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $statement->bind_param(
                    'ssssssssi',
                    $name,
                    $email,
                    $phone,
                    $course,
                    $academicYear,
                    $gender,
                    $status,
                    $hashedPassword,
                    $studentId
                );
            } else {
                $statement = $mysqli->prepare(
                    'UPDATE students SET fullname = ?, email = ?, phone = ?, course = ?,
                     academic_year = ?, gender = ?, status = ? WHERE id = ? AND role = "student"'
                );
                $statement->bind_param(
                    'sssssssi',
                    $name,
                    $email,
                    $phone,
                    $course,
                    $academicYear,
                    $gender,
                    $status,
                    $studentId
                );
            }
            $successText = 'Student details updated successfully.';
        } else {
            if ($password === '') {
                redirectWithMessage('error', 'A password is required for a new student.');
            }
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $statement = $mysqli->prepare(
                'INSERT INTO students
                 (fullname, email, password, phone, course, academic_year, gender, status, role)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, "student")'
            );
            $statement->bind_param(
                'ssssssss',
                $name,
                $email,
                $hashedPassword,
                $phone,
                $course,
                $academicYear,
                $gender,
                $status
            );
            $successText = 'New student added successfully.';
        }

        if (!$statement || !$statement->execute()) {
            $errorText = $mysqli->errno === 1062
                ? 'That email address is already registered.'
                : 'The student record could not be saved.';
            redirectWithMessage('error', $errorText);
        }
        $statement->close();
        recordAuditLog($pdo, $studentId > 0 ? 'UPDATE_STUDENT' : 'CREATE_STUDENT', 'student', $studentId > 0 ? $studentId : null, $successText);
        redirectWithMessage('message', $successText);
    }

    if ($action === 'soft_delete_student' && $studentId > 0) {
        $statement = $mysqli->prepare(
            'UPDATE students SET status = "inactive" WHERE id = ? AND role = "student"'
        );
        $statement->bind_param('i', $studentId);
        if ($statement->execute() && $statement->affected_rows === 1) {
            recordAuditLog($pdo, 'DEACTIVATE_STUDENT', 'student', $studentId, 'Student marked inactive');
            redirectWithMessage('message', 'Student marked inactive successfully.');
        }
        redirectWithMessage('error', 'Student could not be marked inactive.');
    }
}

if (isset($_GET['edit'])) {
    $studentId = (int) $_GET['edit'];
    $statement = $mysqli->prepare(
        'SELECT id, fullname, email, phone, course, academic_year, gender, status
         FROM students WHERE id = ? AND role = "student" LIMIT 1'
    );
    $statement->bind_param('i', $studentId);
    $statement->execute();
    $editingStudent = $statement->get_result()->fetch_assoc() ?: null;
    $statement->close();
}

$search = trim((string) ($_GET['search'] ?? ''));
$courseFilter = trim((string) ($_GET['course'] ?? ''));
$statusFilter = ($_GET['status'] ?? '') === 'inactive' ? 'inactive' : (($_GET['status'] ?? '') === 'active' ? 'active' : '');
$likeSearch = '%' . $search . '%';
$likeCourse = '%' . $courseFilter . '%';
$query = 'SELECT id, fullname, email, phone, course, academic_year, gender, status
          FROM students
          WHERE role = "student"
            AND (fullname LIKE ? OR email LIKE ? OR course LIKE ?)
            AND (? = "" OR course LIKE ?)
            AND (? = "" OR status = ?)
          ORDER BY fullname';
$statement = $mysqli->prepare($query);
$statement->bind_param(
    'sssssss',
    $likeSearch,
    $likeSearch,
    $likeSearch,
    $courseFilter,
    $likeCourse,
    $statusFilter,
    $statusFilter
);
$statement->execute();
$students = $statement->get_result()->fetch_all(MYSQLI_ASSOC);
$statement->close();

$message = (string) ($_GET['message'] ?? '');
$error = (string) ($_GET['error'] ?? '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management - StudentHub</title>
    <link rel="stylesheet" href="style.css?v=20261029">
    <script src="script.js" defer></script>
</head>
<body>
<?php renderPortalHeader(); ?>
<main id="main-content">
    <div class="page-heading">
        <div>
            <p class="eyebrow"></p>
            <h1>Student management</h1>
            <p class="page-subtitle">Add, update, search, filter, soft-delete and export student records.</p>
        </div>
        <a class="btn btn-primary" href="student_management.php?export=csv">Export CSV</a>
    </div>

    <?php if ($message !== ''): ?><div class="admin-alert success"><?= htmlspecialchars($message) ?></div><?php endif; ?>
    <?php if ($error !== ''): ?><div class="admin-alert error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <section class="admin-section">
        <div class="section-heading"><div><p class="eyebrow">CRUD FORM</p><h2><?= $editingStudent ? 'Edit student' : 'Add new student' ?></h2></div></div>
        <form method="post" class="admin-form">
            <input type="hidden" name="action" value="save_student">
            <input type="hidden" name="student_id" value="<?= (int) ($editingStudent['id'] ?? 0) ?>">
            <input type="text" name="fullname" placeholder="Full name" required value="<?= htmlspecialchars((string) ($editingStudent['fullname'] ?? '')) ?>">
            <input type="email" name="email" placeholder="Email" required value="<?= htmlspecialchars((string) ($editingStudent['email'] ?? '')) ?>">
            <input type="password" name="password" placeholder="<?= $editingStudent ? 'New password (optional)' : 'Password' ?>" <?= $editingStudent ? '' : 'required' ?>>
            <input type="text" name="phone" placeholder="Phone" value="<?= htmlspecialchars((string) ($editingStudent['phone'] ?? '')) ?>">
            <input type="text" name="course" placeholder="Course" value="<?= htmlspecialchars((string) ($editingStudent['course'] ?? '')) ?>">
            <input type="text" name="academic_year" placeholder="Academic year" value="<?= htmlspecialchars((string) ($editingStudent['academic_year'] ?? '')) ?>">
            <input type="text" name="gender" placeholder="Gender" value="<?= htmlspecialchars((string) ($editingStudent['gender'] ?? '')) ?>">
            <select name="status"><option value="active" <?= ($editingStudent['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Active</option><option value="inactive" <?= ($editingStudent['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option></select>
            <button class="btn btn-primary" type="submit"><?= $editingStudent ? 'Update student' : 'Add student' ?></button>
            <?php if ($editingStudent): ?><a class="btn btn-light" href="student_management.php">Cancel</a><?php endif; ?>
        </form>
    </section>

    <section class="admin-section">
        <div class="section-heading"><div><p class="eyebrow">SEARCH AND FILTER</p><h2>Student records</h2></div></div>
        <form method="get" class="admin-form">
            <input type="search" name="search" placeholder="Search name, email or course" value="<?= htmlspecialchars($search) ?>">
            <input type="text" name="course" placeholder="Course filter" value="<?= htmlspecialchars($courseFilter) ?>">
            <select name="status"><option value="">All statuses</option><option value="active" <?= $statusFilter === 'active' ? 'selected' : '' ?>>Active</option><option value="inactive" <?= $statusFilter === 'inactive' ? 'selected' : '' ?>>Inactive</option></select>
            <button class="btn btn-primary" type="submit">Search</button>
            <a class="btn btn-light" href="student_management.php">Clear</a>
        </form>
        <div class="admin-table-wrap"><table><thead><tr><th>Name</th><th>Email</th><th>Course</th><th>Year</th><th>Status</th><th>Actions</th></tr></thead><tbody>
        <?php foreach ($students as $student): ?>
            <tr>
                <td><?= htmlspecialchars($student['fullname']) ?></td>
                <td><?= htmlspecialchars($student['email']) ?></td>
                <td><?= htmlspecialchars((string) $student['course']) ?></td>
                <td><?= htmlspecialchars((string) $student['academic_year']) ?></td>
                <td><?= htmlspecialchars(ucfirst($student['status'])) ?></td>
                <td>
                    <a class="btn btn-light" href="student_management.php?edit=<?= (int) $student['id'] ?>">Edit</a>
                    <?php if ($student['status'] === 'active'): ?>
                        <form method="post" style="display:inline" onsubmit="return confirm('Mark this student inactive?');">
                            <input type="hidden" name="action" value="soft_delete_student">
                            <input type="hidden" name="student_id" value="<?= (int) $student['id'] ?>">
                            <button class="btn btn-danger" type="submit">Delete</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$students): ?><tr><td colspan="6">No student records match your search.</td></tr><?php endif; ?>
        </tbody></table></div>
    </section>
</main>
<?php renderPortalFooter(); ?>
</body>
</html>
