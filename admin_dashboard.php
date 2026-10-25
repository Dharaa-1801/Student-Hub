<?php
require_once __DIR__ . '/page_session.php';
requireRole($pdo, 'admin');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - StudentHub</title>
    <link rel="stylesheet" href="style.css?v=20261022">
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
            <div><span class="status-pill">● System administrator</span><h2>Portal control center</h2><p>Manage attendance, students, timetable, events and student queries.</p></div>
            <div class="hero-action"><a class="btn btn-light" href="admin_management.php">Open control center</a></div>
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