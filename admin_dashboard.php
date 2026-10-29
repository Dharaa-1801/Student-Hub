<?php
require_once __DIR__ . '/page_session.php';
requireRole($pdo, 'admin');
require_once __DIR__ . '/audit.php';

$totalUserCount = (int) $pdo->query('SELECT COUNT(*) FROM students')->fetchColumn();
$userCount = (int) $pdo->query("SELECT COUNT(*) FROM students WHERE role = 'student'")->fetchColumn();
$activeUserCount = (int) $pdo->query("SELECT COUNT(*) FROM students WHERE status = 'active'")->fetchColumn();
$eventCount = (int) $pdo->query('SELECT COUNT(*) FROM events')->fetchColumn();
$registrationCount = (int) $pdo->query('SELECT COUNT(*) FROM registrations')->fetchColumn();
$latestRegistrations = $pdo->query(
    'SELECT r.registration_date, s.fullname, e.title
     FROM registrations r
     INNER JOIN students s ON s.id = r.student_id
     INNER JOIN events e ON e.id = r.event_id
     ORDER BY r.registration_date DESC LIMIT 8'
)->fetchAll();
$auditLogs = $pdo->query(
    'SELECT a.action, a.entity_type, a.entity_id, a.details, a.created_at,
            COALESCE(s.fullname, "System") AS admin_name
     FROM audit_logs a LEFT JOIN students s ON s.id = a.user_id
     ORDER BY a.created_at DESC LIMIT 10'
)->fetchAll();
recordAuditLog($pdo, 'VIEW_DASHBOARD', 'dashboard', null, 'Admin dashboard opened');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - StudentHub</title>
    <link rel="stylesheet" href="style.css?v=20261029">
    <script src="script.js" defer></script>
</head>
<body>
    <?php renderPortalHeader(); ?>
    <main id="main-content">
        <div class="page-heading">
            <div>
                <p class="eyebrow">ADMINISTRATION</p>
                <h1>Welcome, <?= htmlspecialchars($_SESSION['fullname']) ?></h1>
                <p class="page-subtitle">Manage the Student Hub portal from one central workspace.</p>
            </div>
            <a class="btn btn-primary" href="admin_management.php">Manage portal</a>
        </div>
        <section class="dashboard-hero admin-hero">
            <div><span class="status-pill">â— System administrator</span><h2>Portal control center</h2><p>Manage attendance, students, timetable, events and student queries.</p></div>
            <div class="hero-action"><a class="btn btn-light" href="admin_management.php">Open control center</a></div>
        </section>
        <section class="dashboard-stats">
            <div class="stat-card"><span>Total accounts</span><strong><?= $totalUserCount ?></strong><small>Registered portal users</small></div>
            <div class="stat-card"><span>Students</span><strong><?= $userCount ?></strong><small><?= $activeUserCount ?> active users</small></div>
            <div class="stat-card"><span>Events</span><strong><?= $eventCount ?></strong><small>Published event records</small></div>
            <div class="stat-card"><span>Registrations</span><strong><?= $registrationCount ?></strong><small>Event registrations</small></div>
        </section>
        <div class="dashboard-grid">
            <a class="dashboard-card" href="admin_management.php#attendance"><span class="card-icon">AT</span><strong>Attendance</strong><small>Update student attendance</small></a>
            <a class="dashboard-card" href="admin_management.php#students"><span class="card-icon">ST</span><strong>Students</strong><small>Remove student accounts</small></a>
            <a class="dashboard-card" href="student_management.php"><span class="card-icon">CR</span><strong>Student CRUD</strong><small>Add, edit, search and export records</small></a>
            <a class="dashboard-card" href="admin_management.php#timetable"><span class="card-icon">TT</span><strong>Timetable</strong><small>Change timetable and schedule</small></a>
            <a class="dashboard-card" href="admin_management.php#events"><span class="card-icon">EV</span><strong>Events</strong><small>Create events and upload posters</small></a>
            <a class="dashboard-card" href="admin_management.php#queries"><span class="card-icon">QU</span><strong>Student queries</strong><small>Review submitted queries</small></a>
            <a class="dashboard-card" href="admin_management.php#materials"><span class="card-icon">SM</span><strong>Study materials</strong><small>Add notes and resources</small></a>
            <a class="dashboard-card" href="admin_management.php#certificates"><span class="card-icon">CR</span><strong>Certificates</strong><small>Generate student certificates</small></a>
        </div>
        <section class="admin-section dashboard-details">
            <div><p class="eyebrow">LATEST REGISTRATIONS</p><h2>Recent event registrations</h2></div>
            <div class="admin-table-wrap"><table><thead><tr><th>Student</th><th>Event</th><th>Registered</th></tr></thead><tbody>
            <?php foreach ($latestRegistrations as $registration): ?><tr><td><?= htmlspecialchars($registration['fullname']) ?></td><td><?= htmlspecialchars($registration['title']) ?></td><td><?= htmlspecialchars($registration['registration_date']) ?></td></tr><?php endforeach; ?>
            <?php if (!$latestRegistrations): ?><tr><td colspan="3">No registrations found.</td></tr><?php endif; ?>
            </tbody></table></div>
        </section>
        <section class="admin-section dashboard-details">
            <div><p class="eyebrow">AUDIT LOG</p><h2>Latest admin actions</h2></div>
            <div class="admin-table-wrap"><table><thead><tr><th>Admin</th><th>Action</th><th>Entity</th><th>Details</th><th>Time</th></tr></thead><tbody>
            <?php foreach ($auditLogs as $log): ?><tr><td><?= htmlspecialchars($log['admin_name']) ?></td><td><?= htmlspecialchars($log['action']) ?></td><td><?= htmlspecialchars($log['entity_type'] . ($log['entity_id'] ? ' #' . $log['entity_id'] : '')) ?></td><td><?= htmlspecialchars((string) $log['details']) ?></td><td><?= htmlspecialchars($log['created_at']) ?></td></tr><?php endforeach; ?>
            </tbody></table></div>
        </section>
        <section class="dashboard-details">
            <div><p class="eyebrow">ADMIN ACCOUNT</p><h2>System administrator</h2><p class="page-subtitle">You have full access to the connected Student Hub pages.</p></div>
            <a class="btn btn-danger" href="logout.php">Sign out</a>
        </section>
    </main>
    <script>
        const timeoutRemaining = <?= sessionTimeoutRemaining() ?>;
        window.setTimeout(() => {
            window.location.replace('timeout.php');
        }, timeoutRemaining * 1000);
    </script>
    <?php renderPortalFooter(); ?>
</body>
</html>