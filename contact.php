<?php require_once __DIR__ . '/page_session.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Student Hub Portal</title>
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
                <a href="index.php">Home</a> &gt; <b>Contact Us</b>
            </p>
        </nav>

        <section aria-labelledby="contact-title">
            <div align="center">
                <h1 id="contact-title">Campus Helpdesk &amp; Support</h1>
                <p>Have questions? Reach out to the respective department coordinators or administrative section.</p>
            </div>
            <hr>
        </section>

        <br>

        <!-- Department Directory Table -->
        <section aria-labelledby="directory-heading">
            <h2 id="directory-heading" align="center">Department Contact Directory</h2>

            <table border="1" width="100%" cellpadding="10">
                <thead>
                    <tr>
                        <th scope="col">Department / Office</th>
                        <th scope="col">Contact Person</th>
                        <th scope="col">Email Address</th>
                        <th scope="col">Helpline Number</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><b>Computer Engineering Dept.</b></td>
                        <td>Head of Department (HOD)</td>
                        <td><a href="mailto:hod.computer@studenthub.edu">hod.computer@studenthub.edu</a></td>
                        <td><a href="tel:+912637200101">+91 (02637) 200101</a></td>
                    </tr>
                    <tr>
                        <td><b>Academic &amp; GTU Section</b></td>
                        <td>Student Admin Officer</td>
                        <td><a href="mailto:academics@studenthub.edu">academics@studenthub.edu</a></td>
                        <td><a href="tel:+912637200102">+91 (02637) 200102</a></td>
                    </tr>
                    <tr>
                        <td><b>Scholarship &amp; Certificate Cell</b></td>
                        <td>Document In-Charge</td>
                        <td><a href="mailto:certificates@studenthub.edu">certificates@studenthub.edu</a></td>
                        <td><a href="tel:+912637200103">+91 (02637) 200103</a></td>
                    </tr>
                    <tr>
                        <td><b>IT &amp; Portal Support</b></td>
                        <td>System Administrator</td>
                        <td><a href="mailto:itsupport@studenthub.edu">itsupport@studenthub.edu</a></td>
                        <td><a href="tel:+912637200104">+91 (02637) 200104</a></td>
                    </tr>
                </tbody>
            </table>
        </section>

        <br>

       <!-- Contact Form Section -->
        <section aria-labelledby="inquiry-heading">
            <h2 id="inquiry-heading" align="center">Send an Official Inquiry</h2>

            <form action="process_form.php" method="POST">
                <table border="1" width="65%" align="center" cellpadding="10">
                    <tbody>
                        <tr>
                            <td><label for="caller-name">Full Name <span aria-hidden="true">*</span></label></td>
                            <td><input type="text" id="caller-name" name="fullname" size="35" required aria-required="true" autocomplete="name"></td>
                        </tr>
                        <tr>
                            <td><label for="caller-email">Email Address <span aria-hidden="true">*</span></label></td>
                            <td><input type="email" id="caller-email" name="email" size="35" required aria-required="true" autocomplete="email"></td>
                        </tr>
                        <tr>
                            <td><label for="inquiry-dept">Select Department <span aria-hidden="true">*</span></label></td>
                            <td>
                                <select id="inquiry-dept" name="course" required aria-required="true">
                                    <option value="">-- Choose Department --</option>
                                    <option value="Computer Engineering">Computer Science &amp; Engg.</option>
                                    <option value="Student Admin Office">Student Admin Office</option>
                                    <option value="Examination Cell">Examination Cell</option>
                                    <option value="IT Support">IT Support</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="message-box">Message / Inquiry <span aria-hidden="true">*</span></label></td>
                            <td>
                                <textarea id="message-box" name="message" rows="5" cols="35" placeholder="Write your inquiry here..." required aria-required="true"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" align="center">
                                <input type="submit" value="Send Message">
                                <input type="reset" value="Reset Form">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </section>

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