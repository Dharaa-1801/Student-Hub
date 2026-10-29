<?php require_once __DIR__ . '/page_session.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academics - Student Hub Portal</title>
    <link rel="stylesheet" href="style.css?v=20261029">
<script src="script.js" defer></script>
</head>

<body>

    <a href="#main-content">Skip to main content</a>

    <?php renderPortalHeader(); ?>
    <br>

    <main id="main-content">
<div style="background-color: #e6f0fa; border: 1px solid #bce8f1; color: #31708f; padding: 12px; border-radius: 6px; margin-bottom: 1.5rem; width: 100%; grid-column: 1 / -1;">
        <strong>Welcome to StudentHub!</strong> Check out your dashboard for the latest notices and academic updates.
    </div>
        <nav aria-label="Breadcrumb">
            <p align="left">
                <a href="index.php">Home</a> &gt; <b>Academics Overview</b>
            </p>
        </nav>

        <section aria-labelledby="dept-title">
            <div align="center">
                <h1 id="dept-title">Department of Computer Science &amp; Engineering</h1>
                <p>Semester 3 Detailed Curriculum, Subject Credits, and Practical Work Allocations</p>
            </div>
            <hr>
        </section>

        <br>

        <!-- Subject Overview & Credit Allocation Table -->
        <section aria-labelledby="subject-overview-heading">
            <h2 id="subject-overview-heading" align="center">Semester 3 Course Modules &amp; Credits</h2>

            <table border="1" width="100%" cellpadding="10">
                <thead>
                    <tr>
                        <th scope="col">Code</th>
                        <th scope="col">Subject Name</th>
                        <th scope="col">Total Credits</th>
                        <th scope="col">Course Type</th>
                        <th scope="col">Assigned Faculty</th>
                        <th scope="col">Syllabus PDF</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td align="center">CEUE203</td>
                        <td><b>Object Oriented Programming</b></td>
                        <td align="center">5 Credits</td>
                        <td>Core Theory + Lab</td>
                        <td>Prof. A. Mehta</td>
                        <td align="center"><a href="#" aria-label="Download OOP Syllabus PDF">Download</a></td>
                    </tr>
                    <tr>
                        <td align="center">MSUD203</td>
                        <td><b>Discrete Mathematics</b></td>
                        <td align="center">4 Credits</td>
                        <td>Theory + Tutorial</td>
                        <td>Prof. R. Shah</td>
                        <td align="center"><a href="#" aria-label="Download Discrete Math Syllabus PDF">Download</a></td>
                    </tr>
                    <tr>
                        <td align="center">CSUC201</td>
                        <td><b>Data Structures &amp; Algorithms</b></td>
                        <td align="center">5 Credits</td>
                        <td>Core Theory + Lab</td>
                        <td>Prof. P. Patel</td>
                        <td align="center"><a href="#" aria-label="Download DSA Syllabus PDF">Download</a></td>
                    </tr>
                    <tr>
                        <td align="center">ITUC201</td>
                        <td><b>Fundamentals of Computer Networks</b></td>
                        <td align="center">4 Credits</td>
                        <td>Theory + Lab</td>
                        <td>Prof. S. Joshi</td>
                        <td align="center"><a href="#" aria-label="Download Computer Networks Syllabus PDF">Download</a></td>
                    </tr>
                </tbody>
            </table>
        </section>

        <br>

        <!-- Practical Lab Allocation Section -->
        <section aria-labelledby="labs-heading">
            <h2 id="labs-heading" align="center">Practical Lab Allocations</h2>

            <table border="1" width="90%" align="center" cellpadding="10">
                <thead>
                    <tr>
                        <th scope="col">Lab Session Title</th>
                        <th scope="col">Lab Venue</th>
                        <th scope="col">Weekly Hours</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Object Oriented Programming Practical Lab</td>
                        <td>Software Lab 2 (Ground Floor)</td>
                        <td align="center">2 Hours / Week</td>
                    </tr>
                    <tr>
                        <td>Data Structures &amp; Algorithms Practical Lab</td>
                        <td>Advanced Computing Lab 1</td>
                        <td align="center">2 Hours / Week</td>
                    </tr>
                    <tr>
                        <td>Computer Networks Configuration Lab</td>
                        <td>Hardware &amp; Networking Lab</td>
                        <td align="center">2 Hours / Week</td>
                    </tr>
                </tbody>
            </table>
        </section>

        <br>

        <!-- Direct Navigation Shortcuts -->
        <nav aria-labelledby="shortcuts-heading">
            <h2 id="shortcuts-heading" align="center">Quick Academic Shortcuts</h2>

            <table border="1" width="70%" align="center" cellpadding="10">
                <tbody>
                    <tr align="center">
                        <td><a href="classschedule.php">Class Timetable</a></td>
                        <td><a href="studymaterials.php">Unit Notes &amp; Slides</a></td>
                        <td><a href="certificates.php">Transcripts &amp; Results</a></td>
                    </tr>
                </tbody>
            </table>
        </nav>

    </main>

    <br>
    <hr>

    <footer role="contentinfo">
        <div align="center">
            <h3>Student Hub Portal Â© 2026</h3>
        </div>
    </footer>

<?php renderPortalFooter(); ?>
</body>

</html>