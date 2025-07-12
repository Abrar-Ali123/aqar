// Modern Template JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Initialize components
    initializeComponents();
    
    // Handle dynamic content loading
    setupDynamicLoading();
    
    // Setup event handlers
    setupEventHandlers();
});

function initializeComponents() {
    // Initialize sliders if any
    const sliders = document.querySelectorAll('.modern-slider');
    sliders.forEach(slider => {
        // Add your slider initialization code here
    });
    
    // Initialize galleries if any
    const galleries = document.querySelectorAll('.modern-gallery');
    galleries.forEach(gallery => {
        // Add your gallery initialization code here
    });
}

function setupDynamicLoading() {
    // Handle lazy loading of images
    const lazyImages = document.querySelectorAll('img[loading="lazy"]');
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    observer.unobserve(img);
                }
            });
        });

        lazyImages.forEach(img => imageObserver.observe(img));
    }
}

function setupEventHandlers() {
    // Handle navigation menu
    const menuToggle = document.querySelector('.menu-toggle');
    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            document.querySelector('.main-nav').classList.toggle('active');
        });
    }
    
    // Handle scroll animations
    window.addEventListener('scroll', function() {
        const header = document.querySelector('.site-header');
        if (header) {
            if (window.scrollY > 100) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        }
    });
}
