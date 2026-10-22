<?php require_once __DIR__ . '/page_session.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Schedule - Student Hub Portal</title>
 <link rel="stylesheet" href="style.css?v=20261022">
<script src="script.js" defer></script>
</head>

<body>

    <!-- Skip Link for Keyboard Accessibility -->
    <a href="#main-content">Skip to main content</a>

    <!-- Header Section -->
   <?php renderPortalHeader(); ?>

    <br>

    <!-- Main Content Area -->
    <main id="main-content">
<div style="background-color: #e6f0fa; border: 1px solid #bce8f1; color: #31708f; padding: 12px; border-radius: 6px; margin-bottom: 1.5rem; width: 100%; grid-column: 1 / -1;">
        <strong>Welcome to StudentHub!</strong> Check out your dashboard for the latest notices and academic updates.
    </div>
        <!-- Intermediate Extension: Breadcrumb Navigation -->
        <nav aria-label="Breadcrumb">
            <p align="left">
                <a href="index.php">Home</a> &gt; <a href="academics.php">Academics</a> &gt; <b>Class Schedule</b>
            </p>
        </nav>

        <section aria-labelledby="schedule-page-title">
            <div align="center">
                <h2 id="schedule-page-title">B.Tech CSE - Semester 3</h2>
                <h3>Weekly Class Schedule</h3>
            </div>
            <hr>

            <!-- Timetable Grid Table Section -->
            <table border="1" width="100%" cellpadding="8" aria-label="Weekly Class Schedule Grid">
                <thead>
                    <tr align="center">
                        <th scope="col">Day</th>
                        <th scope="col"><time datetime="09:10">9:10</time> - <time datetime="10:10">10:10</time></th>
                        <th scope="col"><time datetime="10:10">10:10</time> - <time datetime="11:10">11:10</time></th>
                        <th scope="col"><time datetime="11:10">11:10</time> - <time datetime="12:10">12:10</time></th>
                        <th scope="col"><time datetime="12:10">12:10</time> - <time datetime="13:10">1:10</time></th>
                        <th scope="col"><time datetime="13:10">1:10</time> - <time datetime="14:10">2:10</time></th>
                        <th scope="col"><time datetime="14:10">2:10</time> - <time datetime="14:20">2:20</time></th>
                        <th scope="col"><time datetime="14:20">2:20</time> - <time datetime="15:20">3:20</time></th>
                        <th scope="col"><time datetime="15:20">3:20</time> - <time datetime="16:20">4:20</time></th>
                    </tr>
                </thead>
                <tbody>
                    <tr align="center">
                        <th scope="row">Monday</th>
                        <td>CEUE203<br>OOP</td>
                        <td>MSUD203<br>DM</td>
                        <td rowspan="5"><b>LUNCH BREAK</b></td>
                        <td>CSUC201<br>FDSA</td>
                        <td>ITUC201<br>FCN</td>
                        <td rowspan="5"><b>SHORT BREAK</b></td>
                        <td>ITUE203<br>WDF</td>
                        <td>Library</td>
                    </tr>
                    <tr align="center">
                        <th scope="row">Tuesday</th>
                        <td>ITUE203<br>WDF</td>
                        <td>CEUE203<br>OOP</td>
                        <td>MSUD203<br>DM</td>
                        <td>CSUC201<br>FDSA</td>
                        <td>ITUC201<br>FCN</td>
                        <td>Sports</td>
                    </tr>
                    <tr align="center">
                        <th scope="row">Wednesday</th>
                        <td>CSUC201<br>FDSA</td>
                        <td>ITUC201<br>FCN</td>
                        <td>CEUE203<br>OOP</td>
                        <td>ITUE203<br>WDF</td>
                        <td>Lab Practical</td>
                        <td>Lab Practical</td>
                    </tr>
                    <tr align="center">
                        <th scope="row">Thursday</th>
                        <td>MSUD203<br>DM</td>
                        <td>CSUC201<br>FDSA</td>
                        <td>ITUE203<br>WDF</td>
                        <td>CEUE203<br>OOP</td>
                        <td>ITUC201<br>FCN</td>
                        <td>Seminar</td>
                    </tr>
                    <tr align="center">
                        <th scope="row">Friday</th>
                        <td>ITUC201<br>FCN</td>
                        <td>ITUE203<br>WDF</td>
                        <td>CSUC201<br>FDSA</td>
                        <td>MSUD203<br>DM</td>
                        <td>Project Work</td>
                        <td>Mentoring</td>
                    </tr>
                </tbody>
            </table>
        </section>

        <br>

        <!-- Subject Details Table Section -->
        <section aria-labelledby="subjects-heading">
            <h2 id="subjects-heading" align="center">Subject Details</h2>

            <table border="1" width="90%" align="center" cellpadding="10" aria-labelledby="subjects-heading">
                <thead>
                    <tr>
                        <th scope="col" width="30%">Subject Code</th>
                        <th scope="col">Subject Name</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td align="center">CEUE203</td>
                        <td>Object Oriented Programming</td>
                    </tr>
                    <tr>
                        <td align="center">MSUD203</td>
                        <td>Discrete Mathematics</td>
                    </tr>
                    <tr>
                        <td align="center">CSUC201</td>
                        <td>Fundamentals of Data Structures and Algorithms</td>
                    </tr>
                    <tr>
                        <td align="center">ITUC201</td>
                        <td>Fundamentals of Computer Networks</td>
                    </tr>
                    <tr>
                        <td align="center">ITUE203</td>
                        <td>Web Development Frameworks</td>
                    </tr>
                </tbody>
            </table>
        </section>

        <br>

        <!-- College Timings Table Section -->
        <section aria-labelledby="timings-heading">
            <h2 id="timings-heading" align="center">College Timings</h2>

            <table border="1" width="60%" align="center" cellpadding="10" aria-labelledby="timings-heading">
                <thead>
                    <tr>
                        <th scope="col">Activity</th>
                        <th scope="col">Time</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>College Starts</td>
                        <td align="center"><time datetime="09:10">09:10 AM</time></td>
                    </tr>
                    <tr>
                        <td>Lunch Break</td>
                        <td align="center"><time datetime="11:10">11:10 AM</time> - <time datetime="12:10">12:10 PM</time></td>
                    </tr>
                    <tr>
                        <td>Short Break</td>
                        <td align="center"><time datetime="14:10">02:10 PM</time> - <time datetime="14:20">02:20 PM</time></td>
                    </tr>
                    <tr>
                        <td>College Ends</td>
                        <td align="center"><time datetime="16:20">04:20 PM</time></td>
                    </tr>
                </tbody>
            </table>
        </section>

        <br>

        <!-- Instructions Section -->
        <aside aria-labelledby="instructions-heading">
            <fieldset>
                <legend><b id="instructions-heading">Instructions</b></legend>
                <ul>
                    <li>Students should arrive at least 10 minutes before the first lecture.</li>
                    <li>Carry your ID card daily.</li>
                    <li>Attendance is compulsory for all lectures and practical sessions.</li>
                    <li>Any timetable changes will be announced in the Notices section.</li>
                    <li>Students should maintain discipline during break periods.</li>
                </ul>
            </fieldset>
        </aside>

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