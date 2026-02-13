/**
 * Quran Database - JavaScript
 * Enhanced interactivity for the web application
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Smooth scroll for navigation
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Add loading state for chapter cards
    const chapterCards = document.querySelectorAll('.chapter-card');
    chapterCards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.05}s`;
    });
    
    // Verse highlight on scroll
    const verses = document.querySelectorAll('.verse');
    if (verses.length > 0) {
        const observerOptions = {
            threshold: 0.5,
            rootMargin: '0px 0px -20% 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                }
            });
        }, observerOptions);
        
        verses.forEach(verse => {
            verse.style.opacity = '0.7';
            verse.style.transition = 'opacity 0.3s ease';
            observer.observe(verse);
        });
    }
    
    // Search input auto-focus on desktop
    const searchInput = document.querySelector('.search-input');
    if (searchInput && window.innerWidth > 768) {
        // Don't auto-focus on mobile to prevent keyboard popup
        searchInput.focus();
    }
    
    // Add ripple effect to buttons
    const buttons = document.querySelectorAll('.search-button, .btn-primary');
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple');
            
            this.appendChild(ripple);
            
            setTimeout(() => ripple.remove(), 600);
        });
    });
    
    // Keyboard navigation for chapters
    document.addEventListener('keydown', function(e) {
        const currentUrl = window.location.pathname;
        
        // Only on chapter.php page
        if (currentUrl.includes('chapter.php')) {
            const urlParams = new URLSearchParams(window.location.search);
            const currentId = parseInt(urlParams.get('id')) || 1;
            
            // Left arrow or 'p' for previous
            if ((e.key === 'ArrowLeft' || e.key === 'p') && currentId > 1) {
                window.location.href = `chapter.php?id=${currentId - 1}`;
            }
            
            // Right arrow or 'n' for next
            if ((e.key === 'ArrowRight' || e.key === 'n') && currentId < 114) {
                window.location.href = `chapter.php?id=${currentId + 1}`;
            }
            
            // 'h' for home
            if (e.key === 'h') {
                window.location.href = 'index.php';
            }
        }
    });
    
    // Save scroll position
    if (localStorage.getItem('scrollPosition')) {
        window.scrollTo(0, parseInt(localStorage.getItem('scrollPosition')));
        localStorage.removeItem('scrollPosition');
    }
    
    // Store scroll position when clicking chapter cards
    const links = document.querySelectorAll('.chapter-card');
    links.forEach(link => {
        link.addEventListener('click', function() {
            localStorage.setItem('scrollPosition', window.scrollY);
        });
    });
    
    // Simple analytics logging (could be expanded)
    console.log('Quran Database Web App loaded successfully');
    console.log('May Allah accept this effort 🤲');
});

// Add CSS for ripple effect
const style = document.createElement('style');
style.textContent = `
    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.5);
        transform: scale(0);
        animation: ripple-animation 0.6s ease-out;
        pointer-events: none;
    }
    
    @keyframes ripple-animation {
        to {
            transform: scale(2);
            opacity: 0;
        }
    }
    
    .search-button, .btn-primary {
        position: relative;
        overflow: hidden;
    }
`;
document.head.appendChild(style);
