document.addEventListener('DOMContentLoaded', () => {
    
    // ----------------------------------------------------
    // 1. Dark / Light Theme Switcher with localStorage
    // ----------------------------------------------------
    const themeToggleButton = document.getElementById('theme-toggle');
    
    // Check localStorage preference on page load
    if (localStorage.getItem('theme') === 'dark') {
        document.body.classList.add('dark-theme');
    }

    if (themeToggleButton) {
        themeToggleButton.addEventListener('click', () => {
            document.body.classList.toggle('dark-theme');
            
            // Save preference to localStorage
            if (document.body.classList.contains('dark-theme')) {
                localStorage.setItem('theme', 'dark');
            } else {
                localStorage.setItem('theme', 'light');
            }
        });
    }

    // ----------------------------------------------------
    // 2. Hamburger Menu Toggle for Mobile
    // ----------------------------------------------------
    const menuToggle = document.getElementById('menu-toggle');
    const navList = document.getElementById('nav-list');

    if (menuToggle && navList) {
        menuToggle.addEventListener('click', () => {
            navList.classList.toggle('active');
        });
    }

    // ----------------------------------------------------
    // 3. Modal Popup Logic
    // ----------------------------------------------------
    const openModalBtn = document.getElementById('open-modal-btn');
    const closeModalBtn = document.getElementById('close-modal-btn');
    const demoModal = document.getElementById('demo-modal');

    if (openModalBtn && closeModalBtn && demoModal) {
        openModalBtn.addEventListener('click', () => {
            demoModal.style.display = 'flex';
        });
        closeModalBtn.addEventListener('click', () => {
            demoModal.style.display = 'none';
        });
    }

    // ----------------------------------------------------
    // 4. Content Slider Logic
    // ----------------------------------------------------
    const slides = [
        "Slide 1: Welcome to the Annual Tech Fest 2026!",
        "Slide 2: Semester Examination Schedule is now live.",
        "Slide 3: Register for Sports Week events before Friday."
    ];
    let currentSlide = 0;
    const slideText = document.getElementById('slide-text');
    const prevSlideBtn = document.getElementById('prev-slide');
    const nextSlideBtn = document.getElementById('next-slide');

    if (slideText && prevSlideBtn && nextSlideBtn) {
        nextSlideBtn.addEventListener('click', () => {
            currentSlide = (currentSlide + 1) % slides.length;
            slideText.textContent = slides[currentSlide];
        });

        prevSlideBtn.addEventListener('click', () => {
            currentSlide = (currentSlide - 1 + slides.length) % slides.length;
            slideText.textContent = slides[currentSlide];
        });
    }

    // ----------------------------------------------------
    // 5. Practical 5: Form Frontend Validation
    // ----------------------------------------------------
    const regForm = document.getElementById('registration-form');

    if (regForm) {
        regForm.addEventListener('submit', (e) => {
            e.preventDefault(); // Stop default form submit
            
            let isValid = true;

            // Get form fields
            const fullname = document.getElementById('fullname');
            const email = document.getElementById('email');
            const phone = document.getElementById('phone');
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm-password');

            // Error elements
            const nameError = document.getElementById('name-error');
            const emailError = document.getElementById('email-error');
            const phoneError = document.getElementById('phone-error');
            const passwordError = document.getElementById('password-error');
            const confirmError = document.getElementById('confirm-error');
            const successMsg = document.getElementById('success-message');

            // Reset errors
            [nameError, emailError, phoneError, passwordError, confirmError].forEach(el => {
                if (el) el.style.display = 'none';
            });
            if (successMsg) successMsg.style.display = 'none';

            // 1. Name validation
            if (fullname && fullname.value.trim() === '') {
                nameError.textContent = 'Full name is required.';
                nameError.style.display = 'block';
                isValid = false;
            }

            // 2. Email validation
            const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
            if (email && !email.value.match(emailPattern)) {
                emailError.textContent = 'Please enter a valid email address.';
                emailError.style.display = 'block';
                isValid = false;
            }

            // 3. Phone validation (10 digits)
            const phonePattern = /^[0-9]{10}$/;
            if (phone && !phone.value.match(phonePattern)) {
                phoneError.textContent = 'Please enter a valid 10-digit phone number.';
                phoneError.style.display = 'block';
                isValid = false;
            }

            // 4. Password validation (min 6 chars)
            if (password && password.value.length < 6) {
                passwordError.textContent = 'Password must be at least 6 characters long.';
                passwordError.style.display = 'block';
                isValid = false;
            }

            // 5. Confirm password match
            if (confirmPassword && password && confirmPassword.value !== password.value) {
                confirmError.textContent = 'Passwords do not match.';
                confirmError.style.display = 'block';
                isValid = false;
            }

            // If all checks pass
            if (isValid) {
                if (successMsg) successMsg.style.display = 'block';
                regForm.reset();
            }
        });
    }
});

// --- Login Form Validation ---
const loginForm = document.getElementById('login-form');

if (loginForm) {
    loginForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        let isValid = true;

        const username = document.getElementById('username');
        const loginPassword = document.getElementById('login-password');
        
        const usernameError = document.getElementById('username-error');
        const loginPasswordError = document.getElementById('login-password-error');
        const loginSuccessMsg = document.getElementById('login-success-message');

        // Reset errors
        if (usernameError) usernameError.style.display = 'none';
        if (loginPasswordError) loginPasswordError.style.display = 'none';
        if (loginSuccessMsg) loginSuccessMsg.style.display = 'none';

        // 1. Username / Enrollment check
        if (username && username.value.trim() === '') {
            usernameError.textContent = 'Enrollment No / User ID is required.';
            usernameError.style.display = 'block';
            isValid = false;
        }

        // 2. Password check
        if (loginPassword && loginPassword.value.trim() === '') {
            loginPasswordError.textContent = 'Password is required.';
            loginPasswordError.style.display = 'block';
            isValid = false;
        } else if (loginPassword && loginPassword.value.length < 6) {
            loginPasswordError.textContent = 'Password must be at least 6 characters.';
            loginPasswordError.style.display = 'block';
            isValid = false;
        }

        // If valid
        if (isValid) {
            if (loginSuccessMsg) loginSuccessMsg.style.display = 'block';
            // Tame ahiya user ne dashboard par redirect pan kari sako cho:
            // setTimeout(() => { window.location.href = 'dashboard.html'; }, 1500);
        }
    });
}
// --- Practical 5: Comprehensive Student Registration Validation ---
const regForm = document.getElementById('registration-form');

if (regForm) {
    regForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        let isValid = true;

        // Form Fields
        const fullname = document.getElementById('fullname');
        const email = document.getElementById('email');
        const phone = document.getElementById('phone');
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirm-password');
        const course = document.getElementById('course');
        const year = document.getElementById('year');
        const genderOptions = document.getElementsByName('gender');
        const terms = document.getElementById('terms');

        // Error Elements
        const nameError = document.getElementById('name-error');
        const emailError = document.getElementById('email-error');
        const phoneError = document.getElementById('phone-error');
        const passwordError = document.getElementById('password-error');
        const confirmError = document.getElementById('confirm-error');
        const courseError = document.getElementById('course-error');
        const yearError = document.getElementById('year-error');
        const genderError = document.getElementById('gender-error');
        const termsError = document.getElementById('terms-error');
        const successMsg = document.getElementById('success-message');

        // Reset all errors
        const errors = [nameError, emailError, phoneError, passwordError, confirmError, courseError, yearError, genderError, termsError];
        errors.forEach(el => { if (el) el.style.display = 'none'; });
        if (successMsg) successMsg.style.display = 'none';

        // 1. Full Name Validation (Only letters and spaces)
        const namePattern = /^[A-Za-z\s]{3,}$/;
        if (!fullname || fullname.value.trim() === '') {
            nameError.textContent = 'Full name is required.';
            nameError.style.display = 'block';
            isValid = false;
        } else if (!namePattern.test(fullname.value.trim())) {
            nameError.textContent = 'Name must contain only letters (min 3 characters).';
            nameError.style.display = 'block';
            isValid = false;
        }

        // 2. Email Validation using Regular Expression
        const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!email || !email.value.match(emailPattern)) {
            emailError.textContent = 'Please enter a valid email address (e.g. user@gmail.com).';
            emailError.style.display = 'block';
            isValid = false;
        }

        // 3. Mobile Number Validation (Exact 10 digits using Regex)
        const phonePattern = /^[6-9][0-9]{9}$/;
        if (!phone || phone.value.trim() === '') {
            phoneError.textContent = 'Mobile number is required.';
            phoneError.style.display = 'block';
            isValid = false;
        } else if (!phonePattern.test(phone.value.trim())) {
            phoneError.textContent = 'Enter a valid 10-digit mobile number starting with 6-9.';
            phoneError.style.display = 'block';
            isValid = false;
        }

        // 4. Password Validation (Min 6 chars)
        if (!password || password.value.length < 6) {
            passwordError.textContent = 'Password must be at least 6 characters long.';
            passwordError.style.display = 'block';
            isValid = false;
        }

        // 5. Confirm Password Matching
        if (!confirmPassword || confirmPassword.value !== password.value) {
            confirmError.textContent = 'Passwords do not match.';
            confirmError.style.display = 'block';
            isValid = false;
        }

        // 6. Course Selection Validation
        if (!course || course.value === '') {
            courseError.textContent = 'Please select a course.';
            courseError.style.display = 'block';
            isValid = false;
        }

        // 7. Academic Year Selection Validation
        if (!year || year.value === '') {
            yearError.textContent = 'Please select your academic year.';
            yearError.style.display = 'block';
            isValid = false;
        }

        // 8. Gender Radio Button Validation
        let genderSelected = false;
        for (let i = 0; i < genderOptions.length; i++) {
            if (genderOptions[i].checked) {
                genderSelected = true;
                break;
            }
        }
        if (!genderSelected) {
            genderError.textContent = 'Please select your gender.';
            genderError.style.display = 'block';
            isValid = false;
        }

        // 9. Terms & Conditions Checkbox Validation
        if (!terms || !terms.checked) {
            termsError.textContent = 'You must agree to the terms and conditions.';
            termsError.style.display = 'block';
            isValid = false;
        }

        // Final Success Action
        if (isValid) {
            if (successMsg) successMsg.style.display = 'block';
            regForm.reset();
        }
    });
}