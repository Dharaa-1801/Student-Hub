<?php
declare(strict_types=1);

require_once __DIR__ . '/page_session.php';
requireRole($pdo, 'admin');

$pdo->exec(
    "ALTER TABLE events
     ADD COLUMN IF NOT EXISTS poster_path VARCHAR(255) DEFAULT NULL"
);

$posterDirectory = __DIR__ . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'event-posters';
if (!is_dir($posterDirectory) && !mkdir($posterDirectory, 0755, true) && !is_dir($posterDirectory)) {
    throw new RuntimeException('The event poster upload directory could not be created.');
}

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

function validateEventInput(): array
{
    $title = trim((string) ($_POST['title'] ?? ''));
    $category = trim((string) ($_POST['category'] ?? ''));
    $eventDate = (string) ($_POST['event_date'] ?? '');
    $description = trim((string) ($_POST['description'] ?? ''));

    if ($title === '' || $category === '' || $eventDate === '') {
        throw new InvalidArgumentException('Title, category, and event date are required.');
    }

    $date = DateTime::createFromFormat('Y-m-d', $eventDate);
    if (!$date || $date->format('Y-m-d') !== $eventDate) {
        throw new InvalidArgumentException('Please provide a valid event date.');
    }

    return [$title, $category, $eventDate, $description];
}

function uploadEventPoster(string $posterDirectory): ?string
{
    if (!isset($_FILES['poster']) || $_FILES['poster']['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    $upload = $_FILES['poster'];
    if ($upload['error'] === UPLOAD_ERR_INI_SIZE) {
        throw new InvalidArgumentException('Poster must be 2 MB or smaller.');
    }
    if ($upload['error'] !== UPLOAD_ERR_OK) {
        throw new InvalidArgumentException('Poster upload failed. Please try again.');
    }
    if ((int) $upload['size'] > 2 * 1024 * 1024) {
        throw new InvalidArgumentException('Poster must be 2 MB or smaller.');
    }

    $allowedTypes = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp',
    ];
    $mimeType = (new finfo(FILEINFO_MIME_TYPE))->file($upload['tmp_name']);
    if (!isset($allowedTypes[$mimeType])) {
        throw new InvalidArgumentException('Poster must be a JPG, PNG, GIF, or WEBP image.');
    }

    $filename = bin2hex(random_bytes(16)) . '.' . $allowedTypes[$mimeType];
    if (!move_uploaded_file($upload['tmp_name'], $posterDirectory . DIRECTORY_SEPARATOR . $filename)) {
        throw new RuntimeException('The poster could not be saved.');
    }

    return 'uploads/event-posters/' . $filename;
}

function removeEventPoster(?string $posterPath): void
{
    if ($posterPath === null || $posterPath === '') {
        return;
    }

    $path = __DIR__ . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $posterPath);
    if (is_file($path) && !unlink($path)) {
        error_log('StudentHub could not remove event poster: ' . $path);
    }
}

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
    } elseif (in_array($action, ['event_create', 'event_update'], true)) {
        try {
            [$title, $category, $eventDate, $description] = validateEventInput();
            $posterPath = uploadEventPoster($posterDirectory);
            $eventId = (int) ($_POST['event_id'] ?? 0);

            if ($action === 'event_create') {
                $statement = $pdo->prepare(
                    'INSERT INTO events (title, category, event_date, description, poster_path)
                     VALUES (:title, :category, :event_date, :description, :poster_path)'
                );
                $statement->execute([
                    ':title' => $title,
                    ':category' => $category,
                    ':event_date' => $eventDate,
                    ':description' => $description,
                    ':poster_path' => $posterPath,
                ]);
                $message = 'Event added successfully.';
            } else {
                $existingStatement = $pdo->prepare('SELECT poster_path FROM events WHERE id = :id');
                $existingStatement->execute([':id' => $eventId]);
                $existing = $existingStatement->fetch();
                if (!$existing) {
                    throw new InvalidArgumentException('Event was not found.');
                }

                $statement = $pdo->prepare(
                    'UPDATE events
                     SET title = :title, category = :category, event_date = :event_date,
                         description = :description, poster_path = COALESCE(:poster_path, poster_path)
                     WHERE id = :id'
                );
                $statement->execute([
                    ':title' => $title,
                    ':category' => $category,
                    ':event_date' => $eventDate,
                    ':description' => $description,
                    ':poster_path' => $posterPath,
                    ':id' => $eventId,
                ]);
                if ($posterPath !== null) {
                    removeEventPoster($existing['poster_path']);
                }
                $message = 'Event updated successfully.';
            }
        } catch (InvalidArgumentException | RuntimeException | PDOException $exception) {
            if (isset($posterPath) && $posterPath !== null) {
                removeEventPoster($posterPath);
            }
            $error = $exception->getMessage();
        }
    } elseif ($action === 'event_delete') {
        $eventId = (int) ($_POST['event_id'] ?? 0);
        $existingStatement = $pdo->prepare('SELECT poster_path FROM events WHERE id = :id');
        $existingStatement->execute([':id' => $eventId]);
        $existing = $existingStatement->fetch();
        if (!$existing) {
            $error = 'Event was not found.';
        } else {
            $statement = $pdo->prepare('DELETE FROM events WHERE id = :id');
            $statement->execute([':id' => $eventId]);
            removeEventPoster($existing['poster_path']);
            $message = 'Event deleted successfully.';
        }
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
    'SELECT id, title, category, event_date, description, poster_path
     FROM events ORDER BY event_date DESC LIMIT 20'
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
    <script src="event-api.js" defer></script>
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
        <div id="event-api-status" class="admin-alert" hidden></div>
        <form id="event-api-form" method="post" class="admin-form" enctype="multipart/form-data">
            <input type="hidden" name="action" value="event_create"><input type="text" name="title" placeholder="Event title" required><input type="text" name="category" placeholder="Category" required><input type="date" name="event_date" required><input type="text" name="description" placeholder="Description"><input type="file" name="poster" accept=".jpg,.jpeg,.png,.gif,.webp" aria-label="Event poster (JPG, PNG, GIF, or WEBP; max 2 MB)"><button class="btn btn-primary" type="submit">Add event</button>
        </form>
        <p class="form-help">Poster types: JPG, PNG, GIF, WEBP. Maximum size: 2 MB.</p>
        <div class="admin-table-wrap"><table><thead><tr><th>Poster</th><th>Title</th><th>Category</th><th>Date</th><th>Actions</th></tr></thead><tbody id="event-api-table-body"><?php foreach ($events as $event): ?><tr>
            <td><?php if ($event['poster_path']): ?><img class="event-poster-thumb" src="<?= htmlspecialchars($event['poster_path']) ?>" alt="<?= htmlspecialchars($event['title']) ?> poster"><?php else: ?>No poster<?php endif; ?></td>
            <td><?= htmlspecialchars($event['title']) ?></td><td><?= htmlspecialchars($event['category']) ?></td><td><?= htmlspecialchars($event['event_date']) ?></td>
            <td><details><summary class="btn btn-light">Edit</summary><form method="post" class="admin-form" enctype="multipart/form-data">
                <input type="hidden" name="action" value="event_update"><input type="hidden" name="event_id" value="<?= (int) $event['id'] ?>">
                <input type="text" name="title" value="<?= htmlspecialchars($event['title']) ?>" required><input type="text" name="category" value="<?= htmlspecialchars($event['category']) ?>" required><input type="date" name="event_date" value="<?= htmlspecialchars($event['event_date']) ?>" required><input type="text" name="description" value="<?= htmlspecialchars((string) $event['description']) ?>"><input type="file" name="poster" accept=".jpg,.jpeg,.png,.gif,.webp"><button class="btn btn-primary" type="submit">Save changes</button>
            </form></details><form method="post" onsubmit="return confirm('Delete this event?');"><input type="hidden" name="action" value="event_delete"><input type="hidden" name="event_id" value="<?= (int) $event['id'] ?>"><button class="btn btn-danger" type="submit">Delete</button></form></td>
        </tr><?php endforeach; ?><?php if (!$events): ?><tr><td colspan="5">No events added yet.</td></tr><?php endif; ?></tbody></table></div>
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
