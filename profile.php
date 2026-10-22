<?php
require_once __DIR__ . '/page_session.php';

$profileMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_SESSION['role'] ?? '') === 'admin') {
    $statement = $pdo->prepare(
        'UPDATE students
         SET fullname = :fullname, phone = :phone, course = :course,
             academic_year = :academic_year, gender = :gender
         WHERE id = :id AND role = "admin"'
    );
    $statement->execute([
        ':fullname' => trim((string) ($_POST['fullname'] ?? '')),
        ':phone' => trim((string) ($_POST['phone'] ?? '')),
        ':course' => trim((string) ($_POST['course'] ?? '')),
        ':academic_year' => trim((string) ($_POST['academic_year'] ?? '')),
        ':gender' => trim((string) ($_POST['gender'] ?? '')),
        ':id' => (int) $_SESSION['user_id'],
    ]);
    $_SESSION['last_activity'] = time();
    $currentUser = getCurrentUser($pdo);
    $_SESSION['fullname'] = $currentUser['fullname'];
    $_SESSION['phone'] = $currentUser['phone'];
    $_SESSION['course'] = $currentUser['course'];
    $_SESSION['academic_year'] = $currentUser['academic_year'];
    $_SESSION['gender'] = $currentUser['gender'];
    $profileMessage = 'Admin profile updated successfully.';
}

$profileName = htmlspecialchars((string) ($currentUser['fullname'] ?? ''), ENT_QUOTES, 'UTF-8');
$profileEmail = htmlspecialchars((string) ($currentUser['email'] ?? ''), ENT_QUOTES, 'UTF-8');
$profilePhone = htmlspecialchars((string) ($currentUser['phone'] ?? 'Not provided'), ENT_QUOTES, 'UTF-8');
$profileCourse = htmlspecialchars((string) ($currentUser['course'] ?? 'Not provided'), ENT_QUOTES, 'UTF-8');
$profileYear = htmlspecialchars((string) ($currentUser['academic_year'] ?? 'Not provided'), ENT_QUOTES, 'UTF-8');
$profileGender = htmlspecialchars((string) ($currentUser['gender'] ?? 'Not provided'), ENT_QUOTES, 'UTF-8');
$profilePhoto = !empty($currentUser['profile_photo'])
    ? htmlspecialchars($currentUser['profile_photo'], ENT_QUOTES, 'UTF-8')
    : '22357.jpg';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Student Hub Portal</title>
    <link rel="stylesheet" href="style.css?v=20261022">
    <script src="script.js" defer></script>
</head>

<body>

    <a href="#main-content">Skip to main content</a>

    <!-- Header Section -->
   <?php renderPortalHeader(); ?>

    <br>

    <!-- Main Content Area -->
    <main id="main-content">
<div style="background-color: #e6f0fa; border: 1px solid #bce8f1; color: #31708f; padding: 12px; border-radius: 6px; margin-bottom: 1.5rem; width: 100%; grid-column: 1 / -1;">
        <strong>Welcome to StudentHub!</strong> Check out your dashboard for the latest notices and academic updates.
    </div>
        <!-- Breadcrumb Navigation -->
        <nav aria-label="Breadcrumb">
            <p align="left">
                <a href="index.php">Home</a> &gt; <b>Student Activity &amp; Timeline</b>
            </p>
        </nav>

        <br>

        <!-- SECTION 1: Top Identity Banner -->
        <section aria-labelledby="student-identity-heading">
            <h2 id="student-identity-heading" align="center">Quick Identity Overview</h2>

            <table border="1" width="85%" align="center" cellpadding="12">
                <tbody>
                    <tr>
                        <td width="20%" align="center">
                            <img src="<?= $profilePhoto ?>" width="110" height="130" alt="Student Profile Photo">
                        </td>
                        <td width="40%">
                            <h3><?= $profileName ?></h3>
                            <p><b>Phone:</b> <?= $profilePhone ?></p>
                            <p><b>Course:</b> <?= $profileCourse ?></p>
                            <p><b>Academic Year:</b> <?= $profileYear ?></p>
                        </td>
                        <td width="40%">
                            <p><b>Gender:</b> <?= $profileGender ?></p>
                            <p><b>Current CGPA:</b> 8.75 / 10</p>
                            <p><b>Attendance Score:</b> 91%</p>
                            <p><b>Email:</b> <?= $profileEmail ?></p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>

        <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
        <section aria-labelledby="admin-profile-heading">
            <h2 id="admin-profile-heading">Update administrator profile</h2>
            <?php if ($profileMessage !== ''): ?><div class="admin-alert success"><?= htmlspecialchars($profileMessage) ?></div><?php endif; ?>
            <form method="post" class="admin-form">
                <input type="text" name="fullname" value="<?= $profileName ?>" placeholder="Full name" required>
                <input type="tel" name="phone" value="<?= $profilePhone ?>" placeholder="Phone">
                <input type="text" name="course" value="<?= $profileCourse ?>" placeholder="Department or course">
                <input type="text" name="academic_year" value="<?= $profileYear ?>" placeholder="Academic year">
                <input type="text" name="gender" value="<?= $profileGender ?>" placeholder="Gender">
                <button class="btn btn-primary" type="submit">Save admin profile</button>
            </form>
        </section>
        <?php endif; ?>

        <br>

        <!-- SECTION 2: Academic & Activity Timeline -->
        <section aria-labelledby="activity-timeline-heading">
            <h2 id="activity-timeline-heading" align="center">Academic Journey &amp; Milestones</h2>

            <table border="1" width="85%" align="center" cellpadding="10">
                <thead>
                    <tr>
                        <th scope="col" width="20%">Date / Term</th>
                        <th scope="col" width="25%">Category</th>
                        <th scope="col">Milestone Details</th>
                        <th scope="col" width="15%">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td align="center">August 2026</td>
                        <td>Coursework</td>
                        <td>Enrolled in Semester 3 Core Engineering Modules (OOP, DSA, Discrete Math)</td>
                        <td align="center">Active</td>
                    </tr>
                    <tr>
                        <td align="center">April 2026</td>
                        <td>Academic Project</td>
                        <td>Designed &amp; Submitted Digital Electronics Smart Door Lock Sequence Detector Project</td>
                        <td align="center">Completed</td>
                    </tr>
                    <tr>
                        <td align="center">January 2026</td>
                        <td>Campus Event</td>
                        <td>Participated in "Sales Man of the Year" Entrepreneurship Competition</td>
                        <td align="center">Participated</td>
                    </tr>
                    <tr>
                        <td align="center">Semester 1 &amp; 2</td>
                        <td>University Exams</td>
                        <td>Cleared both semesters with First Class Distinction (Zero Backlogs)</td>
                        <td align="center">Passed</td>
                    </tr>
                </tbody>
            </table>
        </section>

        <br>

        <!-- SECTION 3: Current Semester Submissions & Tasks -->
        <section aria-labelledby="tasks-heading">
            <h2 id="tasks-heading" align="center">Current Tasks &amp; Submissions Status</h2>

            <table border="1" width="85%" align="center" cellpadding="10">
                <thead>
                    <tr>
                        <th scope="col">Subject Title</th>
                        <th scope="col">Task Description</th>
                        <th scope="col">Due Date</th>
                        <th scope="col">Submission Link</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Object Oriented Programming</td>
                        <td>Practical Assignment - Class &amp; Inheritance Lab Work</td>
                        <td>Upcoming</td>
                        <td align="center"><a href="studymaterials.php">Submit File</a></td>
                    </tr>
                    <tr>
                        <td>Data Structures &amp; Algorithms</td>
                        <td>Lab Sheet 2 - Linked List Implementation</td>
                        <td>Upcoming</td>
                        <td align="center"><a href="studymaterials.php">Submit File</a></td>
                    </tr>
                </tbody>
            </table>
        </section>

    </main>

    <br>
    <hr>

    <!-- Footer Section -->
    <footer role="contentinfo">
        <div align="center">
            <h3>Student Hub Portal © 2026</h3>
        </div>
    </footer>

<?php renderPortalFooter(); ?>
</body>

</html>