<?php
declare(strict_types=1);

require_once __DIR__ . '/page_session.php';
requireRole($pdo, 'admin');

$pdo->exec(
    'CREATE TABLE IF NOT EXISTS attendance (
        id INT AUTO_INCREMENT PRIMARY KEY,
        student_id INT NOT NULL,
        subject VARCHAR(100) NOT NULL,
        attendance_date DATE NOT NULL,
        status ENUM("present", "absent") NOT NULL,
        UNIQUE KEY attendance_entry (student_id, subject, attendance_date),
        FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
    )'
);
$pdo->exec(
    'CREATE TABLE IF NOT EXISTS timetable (
        id INT AUTO_INCREMENT PRIMARY KEY,
        day_name VARCHAR(20) NOT NULL,
        start_time TIME NOT NULL,
        end_time TIME NOT NULL,
        subject VARCHAR(100) NOT NULL,
        room VARCHAR(50) DEFAULT NULL
    )'
);
$pdo->exec(
    'CREATE TABLE IF NOT EXISTS study_materials (
        id INT AUTO_INCREMENT PRIMARY KEY,
        subject_code VARCHAR(30) NOT NULL,
        subject_name VARCHAR(150) NOT NULL,
        material_type VARCHAR(50) NOT NULL,
        title VARCHAR(150) NOT NULL,
        resource_url VARCHAR(500) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )'
);
$pdo->exec(
    'CREATE TABLE IF NOT EXISTS certificates (
        id INT AUTO_INCREMENT PRIMARY KEY,
        student_id INT NOT NULL,
        certificate_type VARCHAR(100) NOT NULL,
        certificate_number VARCHAR(50) NOT NULL UNIQUE,
        issued_at DATE NOT NULL,
        status ENUM("issued", "revoked") NOT NULL DEFAULT "issued",
        FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
    )'
);

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = (string) ($_POST['action'] ?? '');

    if ($action === 'remove_student') {
        $studentId = (int) ($_POST['student_id'] ?? 0);
        $statement = $pdo->prepare("DELETE FROM students WHERE id = :id AND role = 'student'");
        $statement->execute([':id' => $studentId]);
        $message = $statement->rowCount() === 1
            ? 'Student account removed successfully.'
            : 'Student account was not found.';
    } elseif ($action === 'attendance') {
        $statement = $pdo->prepare(
            'INSERT INTO attendance (student_id, subject, attendance_date, status)
             VALUES (:student_id, :subject, :attendance_date, :status)
             ON DUPLICATE KEY UPDATE status = VALUES(status)'
        );
        $statement->execute([
            ':student_id' => (int) ($_POST['student_id'] ?? 0),
            ':subject' => trim((string) ($_POST['subject'] ?? '')),
            ':attendance_date' => (string) ($_POST['attendance_date'] ?? ''),
            ':status' => (string) ($_POST['status'] ?? 'absent'),
        ]);
        $message = 'Attendance saved successfully.';
    } elseif ($action === 'timetable') {
        $statement = $pdo->prepare(
            'INSERT INTO timetable (day_name, start_time, end_time, subject, room)
             VALUES (:day_name, :start_time, :end_time, :subject, :room)'
        );
        $statement->execute([
            ':day_name' => trim((string) ($_POST['day_name'] ?? '')),
            ':start_time' => (string) ($_POST['start_time'] ?? ''),
            ':end_time' => (string) ($_POST['end_time'] ?? ''),
            ':subject' => trim((string) ($_POST['timetable_subject'] ?? '')),
            ':room' => trim((string) ($_POST['room'] ?? '')),
        ]);
        $message = 'Timetable entry added successfully.';
    } elseif ($action === 'event') {
        $statement = $pdo->prepare(
            'INSERT INTO events (title, category, event_date, description)
             VALUES (:title, :category, :event_date, :description)'
        );
        $statement->execute([
            ':title' => trim((string) ($_POST['title'] ?? '')),
            ':category' => trim((string) ($_POST['category'] ?? '')),
            ':event_date' => (string) ($_POST['event_date'] ?? ''),
            ':description' => trim((string) ($_POST['description'] ?? '')),
        ]);
        $message = 'Event added successfully.';
    } elseif ($action === 'material') {
        $statement = $pdo->prepare(
            'INSERT INTO study_materials
             (subject_code, subject_name, material_type, title, resource_url)
             VALUES (:subject_code, :subject_name, :material_type, :title, :resource_url)'
        );
        $statement->execute([
            ':subject_code' => trim((string) ($_POST['subject_code'] ?? '')),
            ':subject_name' => trim((string) ($_POST['subject_name'] ?? '')),
            ':material_type' => trim((string) ($_POST['material_type'] ?? '')),
            ':title' => trim((string) ($_POST['material_title'] ?? '')),
            ':resource_url' => trim((string) ($_POST['resource_url'] ?? '')),
        ]);
        $message = 'Study material added successfully.';
    } elseif ($action === 'certificate') {
        $certificateNumber = 'SH-' . date('Y') . '-' . strtoupper(bin2hex(random_bytes(4)));
        $statement = $pdo->prepare(
            'INSERT INTO certificates
             (student_id, certificate_type, certificate_number, issued_at)
             VALUES (:student_id, :certificate_type, :certificate_number, :issued_at)'
        );
        $statement->execute([
            ':student_id' => (int) ($_POST['certificate_student_id'] ?? 0),
            ':certificate_type' => trim((string) ($_POST['certificate_type'] ?? '')),
            ':certificate_number' => $certificateNumber,
            ':issued_at' => date('Y-m-d'),
        ]);
        $message = 'Certificate generated: ' . $certificateNumber;
    }
}

$students = $pdo->query(
    "SELECT id, fullname, email, course, academic_year
     FROM students WHERE role = 'student' ORDER BY fullname"
)->fetchAll();
$events = $pdo->query(
    'SELECT title, category, event_date FROM events ORDER BY event_date DESC LIMIT 8'
)->fetchAll();
$timetable = $pdo->query(
    'SELECT day_name, start_time, end_time, subject, room
     FROM timetable ORDER BY FIELD(day_name, "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"), start_time
     LIMIT 20'
)->fetchAll();
$materials = $pdo->query(
    'SELECT subject_code, subject_name, material_type, title, resource_url
     FROM study_materials ORDER BY created_at DESC LIMIT 20'
)->fetchAll();
$certificates = $pdo->query(
    'SELECT c.certificate_number, c.certificate_type, c.issued_at, s.fullname
     FROM certificates c INNER JOIN students s ON s.id = c.student_id
     ORDER BY c.issued_at DESC LIMIT 20'
)->fetchAll();

$queries = [];
$queryFile = __DIR__ . '/submissions.csv';
if (is_readable($queryFile) && ($handle = fopen($queryFile, 'r')) !== false) {
    $headers = fgetcsv($handle);
    while (($row = fgetcsv($handle)) !== false) {
        if ($headers && count($row) >= count($headers)) {
            $queries[] = array_combine($headers, array_slice($row, 0, count($headers)));
        }
    }
    fclose($handle);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Control Center - StudentHub</title>
    <link rel="stylesheet" href="style.css?v=20261022">
    <script src="script.js" defer></script>
</head>
<body>
<?php renderPortalHeader(); ?>
<main id="main-content">
    <div class="page-heading">
        <div>
            <p class="eyebrow">ADMIN ONLY</p>
            <h1>Admin control center</h1>
            <p class="page-subtitle">These management tools are available only to authorized administrators.</p>
        </div>
    </div>

    <?php if ($message !== ''): ?><div class="admin-alert success"><?= htmlspecialchars($message) ?></div><?php endif; ?>
    <?php if ($error !== ''): ?><div class="admin-alert error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <section id="attendance" class="admin-section">
        <div class="section-heading"><div><p class="eyebrow">ACADEMIC MANAGEMENT</p><h2>Mark attendance</h2></div></div>
        <form method="post" class="admin-form">
            <input type="hidden" name="action" value="attendance">
            <select name="student_id" required><option value="">Select student</option><?php foreach ($students as $student): ?><option value="<?= (int) $student['id'] ?>"><?= htmlspecialchars($student['fullname']) ?> (<?= htmlspecialchars($student['email']) ?>)</option><?php endforeach; ?></select>
            <input type="text" name="subject" placeholder="Subject" required>
            <input type="date" name="attendance_date" required>
            <select name="status"><option value="present">Present</option><option value="absent">Absent</option></select>
            <button class="btn btn-primary" type="submit">Save attendance</button>
        </form>
    </section>

    <section id="students" class="admin-section">
        <div class="section-heading"><div><p class="eyebrow">USER MANAGEMENT</p><h2>Student accounts</h2></div></div>
        <div class="admin-table-wrap"><table><thead><tr><th>Name</th><th>Email</th><th>Course</th><th>Action</th></tr></thead><tbody>
        <?php foreach ($students as $student): ?><tr><td><?= htmlspecialchars($student['fullname']) ?></td><td><?= htmlspecialchars($student['email']) ?></td><td><?= htmlspecialchars((string) $student['course']) ?></td><td><form method="post" onsubmit="return confirm('Remove this student account?');"><input type="hidden" name="action" value="remove_student"><input type="hidden" name="student_id" value="<?= (int) $student['id'] ?>"><button class="btn btn-danger" type="submit">Remove</button></form></td></tr><?php endforeach; ?>
        </tbody></table></div>
    </section>

    <section id="timetable" class="admin-section">
        <div class="section-heading"><div><p class="eyebrow">SCHEDULE MANAGEMENT</p><h2>Add timetable entry</h2></div></div>
        <form method="post" class="admin-form">
            <input type="hidden" name="action" value="timetable">
            <select name="day_name" required><option value="">Day</option><?php foreach (['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'] as $day): ?><option><?= $day ?></option><?php endforeach; ?></select>
            <input type="time" name="start_time" required><input type="time" name="end_time" required>
            <input type="text" name="timetable_subject" placeholder="Subject" required><input type="text" name="room" placeholder="Room">
            <button class="btn btn-primary" type="submit">Save schedule</button>
        </form>
        <div class="admin-table-wrap"><table><thead><tr><th>Day</th><th>Time</th><th>Subject</th><th>Room</th></tr></thead><tbody><?php foreach ($timetable as $entry): ?><tr><td><?= htmlspecialchars($entry['day_name']) ?></td><td><?= htmlspecialchars(substr($entry['start_time'], 0, 5)) ?> - <?= htmlspecialchars(substr($entry['end_time'], 0, 5)) ?></td><td><?= htmlspecialchars($entry['subject']) ?></td><td><?= htmlspecialchars((string) $entry['room']) ?></td></tr><?php endforeach; ?></tbody></table></div>
    </section>

    <section id="events" class="admin-section">
        <div class="section-heading"><div><p class="eyebrow">CAMPUS MANAGEMENT</p><h2>Create event entry</h2></div></div>
        <form method="post" class="admin-form">
            <input type="hidden" name="action" value="event"><input type="text" name="title" placeholder="Event title" required><input type="text" name="category" placeholder="Category" required><input type="date" name="event_date" required><input type="text" name="description" placeholder="Description"><button class="btn btn-primary" type="submit">Add event</button>
        </form>
        <div class="admin-table-wrap"><table><thead><tr><th>Title</th><th>Category</th><th>Date</th></tr></thead><tbody><?php foreach ($events as $event): ?><tr><td><?= htmlspecialchars($event['title']) ?></td><td><?= htmlspecialchars($event['category']) ?></td><td><?= htmlspecialchars($event['event_date']) ?></td></tr><?php endforeach; ?></tbody></table></div>
    </section>

    <section id="materials" class="admin-section">
        <div class="section-heading"><div><p class="eyebrow">ACADEMIC CONTENT</p><h2>Add study material</h2></div></div>
        <form method="post" class="admin-form">
            <input type="hidden" name="action" value="material">
            <input type="text" name="subject_code" placeholder="Subject code" required>
            <input type="text" name="subject_name" placeholder="Subject name" required>
            <select name="material_type" required><option value="">Material type</option><option>Notes</option><option>PPT</option><option>Assignment</option><option>Previous paper</option></select>
            <input type="text" name="material_title" placeholder="Material title" required>
            <input type="url" name="resource_url" placeholder="https://resource-link" required>
            <button class="btn btn-primary" type="submit">Add material</button>
        </form>
        <div class="admin-table-wrap"><table><thead><tr><th>Subject</th><th>Type</th><th>Title</th><th>Resource</th></tr></thead><tbody><?php foreach ($materials as $material): ?><tr><td><?= htmlspecialchars($material['subject_code'] . ' - ' . $material['subject_name']) ?></td><td><?= htmlspecialchars($material['material_type']) ?></td><td><?= htmlspecialchars($material['title']) ?></td><td><a href="<?= htmlspecialchars($material['resource_url']) ?>" target="_blank" rel="noopener">Open</a></td></tr><?php endforeach; ?><?php if (!$materials): ?><tr><td colspan="4">No study materials added yet.</td></tr><?php endif; ?></tbody></table></div>
    </section>

    <section id="certificates" class="admin-section">
        <div class="section-heading"><div><p class="eyebrow">DOCUMENT MANAGEMENT</p><h2>Generate certificate</h2></div></div>
        <form method="post" class="admin-form">
            <input type="hidden" name="action" value="certificate">
            <select name="certificate_student_id" required><option value="">Select student</option><?php foreach ($students as $student): ?><option value="<?= (int) $student['id'] ?>"><?= htmlspecialchars($student['fullname']) ?> (<?= htmlspecialchars($student['email']) ?>)</option><?php endforeach; ?></select>
            <input type="text" name="certificate_type" placeholder="Certificate type" required>
            <button class="btn btn-primary" type="submit">Generate certificate</button>
        </form>
        <div class="admin-table-wrap"><table><thead><tr><th>Student</th><th>Certificate</th><th>Number</th><th>Issued</th></tr></thead><tbody><?php foreach ($certificates as $certificate): ?><tr><td><?= htmlspecialchars($certificate['fullname']) ?></td><td><?= htmlspecialchars($certificate['certificate_type']) ?></td><td><?= htmlspecialchars($certificate['certificate_number']) ?></td><td><?= htmlspecialchars($certificate['issued_at']) ?></td></tr><?php endforeach; ?><?php if (!$certificates): ?><tr><td colspan="4">No certificates generated yet.</td></tr><?php endif; ?></tbody></table></div>
    </section>

    <section id="queries" class="admin-section">
        <div class="section-heading"><div><p class="eyebrow">COMMUNICATIONS</p><h2>Student queries</h2></div></div>
        <div class="admin-table-wrap"><table><thead><tr><th>Name</th><th>Email</th><th>Department</th><th>Message</th><th>Received</th></tr></thead><tbody><?php foreach ($queries as $query): ?><tr><td><?= htmlspecialchars((string) ($query['Full Name'] ?? '')) ?></td><td><?= htmlspecialchars((string) ($query['Email'] ?? '')) ?></td><td><?= htmlspecialchars((string) ($query['Department'] ?? '')) ?></td><td><?= htmlspecialchars((string) ($query['Message'] ?? '')) ?></td><td><?= htmlspecialchars((string) ($query['Timestamp'] ?? '')) ?></td></tr><?php endforeach; ?><?php if (!$queries): ?><tr><td colspan="5">No student queries have been submitted.</td></tr><?php endif; ?></tbody></table></div>
    </section>
</main>
<?php renderPortalFooter(); ?>
</body>
</html>
