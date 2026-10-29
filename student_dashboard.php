<?php
require_once __DIR__ . '/page_session.php';
requireRole($pdo, 'student');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - StudentHub</title>
    <link rel="stylesheet" href="style.css?v=20261029">
    <script src="script.js" defer></script>
</head>
<body>
    <?php renderPortalHeader(); ?>
    <main id="main-content">
        <div class="page-heading">
            <div>
                <p class="eyebrow">STUDENT WORKSPACE</p>
                <h1>Good day, <?= htmlspecialchars($_SESSION['fullname']) ?></h1>
                <p class="page-subtitle">Your academic overview and quick access to portal services.</p>
            </div>
            <a class="btn btn-primary" href="profile.php">View profile</a>
        </div>
        <section class="dashboard-hero">
            <div><span class="status-pill">â— Active student</span><h2>Your learning journey starts here</h2><p>Stay up to date with classes, notices, study material and campus events.</p></div>
            <div class="hero-action"><a class="btn btn-light" href="notice.php">View latest notices</a></div>
        </section>
        <div class="dashboard-grid">
            <a class="dashboard-card" href="academics.php"><span class="card-icon">AC</span><strong>Academics</strong><small>Subjects, syllabus and results</small></a>
            <a class="dashboard-card" href="classschedule.php"><span class="card-icon">CL</span><strong>Class schedule</strong><small>View your weekly timetable</small></a>
            <a class="dashboard-card" href="studymaterials.php"><span class="card-icon">SM</span><strong>Study materials</strong><small>Access notes and resources</small></a>
            <a class="dashboard-card" href="events.php"><span class="card-icon">EV</span><strong>Campus events</strong><small>Discover upcoming activities</small></a>
        </div>
        <section class="dashboard-details">
            <div><p class="eyebrow">ACCOUNT DETAILS</p><h2>Profile information</h2></div>
            <dl class="profile-facts">
                <div><dt>Email</dt><dd><?= htmlspecialchars($_SESSION['email']) ?></dd></div>
                <div><dt>Course</dt><dd><?= htmlspecialchars($_SESSION['course'] ?? 'Not provided') ?></dd></div>
                <div><dt>Academic year</dt><dd><?= htmlspecialchars($_SESSION['academic_year'] ?? 'Not provided') ?></dd></div>
            </dl>
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