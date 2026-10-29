<?php require_once __DIR__ . '/page_session.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration - StudentHub</title>
    <link rel="stylesheet" href="style.css?v=20261029">
    <script src="script.js?v=2" defer></script>
</head>
<body>
    <?php renderPortalHeader(); ?>

    <main id="main-content">
        <h1>Student Registration Portal</h1>
        <hr>
        
        <section>
             <form id="registration-form" action="register_process.php" method="POST" enctype="multipart/form-data">
                
                <!-- Full Name -->
                <div class="form-group">
                    <label for="fullname">Full Name:</label>
                    <input type="text" id="fullname" name="fullname" placeholder="Enter your full name">
                    <small id="name-error" style="color: red; display: none; margin-top: 4px;"></small>
                </div>

                <!-- Email Address -->
                <div class="form-group">
                    <label for="email">Email Address:</label>
                    <input type="email" id="email" name="email" placeholder="studentId@charusat.edu.in">
                    <small id="email-error" style="color: red; display: none; margin-top: 4px;"></small>
                </div>

                <!-- Mobile Number -->
                <div class="form-group">
                    <label for="phone">Mobile Number:</label>
                    <input type="tel" id="phone" name="phone" placeholder="10-digit mobile number" maxlength="10">
                    <small id="phone-error" style="color: red; display: none; margin-top: 4px;"></small>
                </div>

                <div class="form-group">
                    <label for="profile_photo">Profile Photo:</label>
                    <input type="file" id="profile_photo" name="profile_photo" accept="image/jpeg,image/png,image/webp">
                    <small>Optional. JPG, PNG, or WEBP; maximum size 2 MB.</small>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="At least 6 characters">
                    <small id="password-error" style="color: red; display: none; margin-top: 4px;"></small>
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="confirm-password">Confirm Password:</label>
                    <input type="password" id="confirm-password" name="confirm-password" placeholder="Re-enter password">
                    <small id="confirm-error" style="color: red; display: none; margin-top: 4px;"></small>
                </div>

                <!-- Course Selection -->
                <div class="form-group">
                    <label for="course">Select Course:</label>
                    <select id="course" name="course">
                        <option value="">-- Choose Course --</option>
                        <option value="BTech">B.Tech Computer Engineering</option>
                        <option value="BCA">BCA</option>
                        <option value="MCA">MCA</option>
                        <option value="BSc">B.Sc IT</option>
                    </select>
                    <small id="course-error" style="color: red; display: none; margin-top: 4px;"></small>
                </div>

                <!-- Academic Year -->
                <div class="form-group">
                    <label for="year">Academic Year:</label>
                    <select id="year" name="year">
                        <option value="">-- Choose Year --</option>
                        <option value="1">1st Year</option>
                        <option value="2">2nd Year</option>
                        <option value="3">3rd Year</option>
                        <option value="4">4th Year</option>
                    </select>
                    <small id="year-error" style="color: red; display: none; margin-top: 4px;"></small>
                </div>

                <!-- Gender -->
                <div class="form-group">
                    <label>Gender:</label>
                    <div style="display: flex; gap: 20px; margin-top: 6px;">
                        <label style="font-weight: normal;"><input type="radio" name="gender" value="Male"> Male</label>
                        <label style="font-weight: normal;"><input type="radio" name="gender" value="Female"> Female</label>
                        <label style="font-weight: normal;"><input type="radio" name="gender" value="Other"> Other</label>
                    </div>
                    <small id="gender-error" style="color: red; display: none; margin-top: 4px;"></small>
                </div>

                <!-- Terms & Conditions Acceptance -->
                <div class="form-group" style="margin-top: 1rem;">
                    <label style="font-weight: normal; display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" id="terms" name="terms"> I agree to the terms and conditions
                    </label>
                    <small id="terms-error" style="color: red; display: none; margin-top: 4px;"></small>
                </div>

                <!-- Buttons -->
                <div class="button-group" style="margin-top: 1.5rem;">
                    <input type="submit" value="Register Now" name="submit">
                    <input type="reset" value="Clear">
                </div>
            </form>

            <div id="success-message" style="display: none; background: #d1e7dd; color: #0f5132; padding: 12px; border-radius: 6px; margin-top: 1rem; font-weight: bold; text-align: center;">
                Registration successful! Welcome to StudentHub Portal.
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2026 StudentHub Portal. All rights reserved.</p>
    </footer>
<?php renderPortalFooter(); ?>
</body>
</html>