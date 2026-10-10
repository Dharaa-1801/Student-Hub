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
    // 3. Dynamic Notification Banner
    // ----------------------------------------------------
    const mainContainer = document.querySelector('main');
    if (mainContainer) {
        const banner = document.createElement('div');
        banner.className = 'alert alert-info mt-3 p-3 mb-3';
        banner.style.backgroundColor = '#e6f0fa';
        banner.style.borderRadius = '6px';
        banner.style.border = '1px solid #bce8f1';
        banner.style.color = '#31708f';
        banner.style.padding = '12px';
        banner.style.marginBottom = '1.5rem';
        banner.innerHTML = '<strong>Welcome to StudentHub!</strong> Check out your dashboard for the latest notices and academic updates.';
        
        mainContainer.prepend(banner);
    }
});
// --- Modal Popup Logic ---
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

// --- Content Slider Logic ---
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