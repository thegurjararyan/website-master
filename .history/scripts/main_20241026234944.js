// scripts/main.js

function loadComponent(componentName, targetElementId) {
    const targetElement = document.getElementById(targetElementId);
    
    // Load HTML
    fetch(`components/${componentName}/${componentName}.html`)
        .then(response => response.text())
        .then(html => {
            targetElement.innerHTML = html;
            
            // Load and apply CSS
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = `components/${componentName}/${componentName}.css`;
            document.head.appendChild(link);
            
            // Load and execute JS
            const script = document.createElement('script');
            script.src = `components/${componentName}/${componentName}.js`;
            document.body.appendChild(script);
        });
}

function scrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Show/hide scroll-to-top button
window.addEventListener('scroll', function() {
    const scrollToTopBtn = document.getElementById('scrollToTop');
    if (window.pageYOffset > 300) {
        scrollToTopBtn.classList.add('visible');
    } else {
        scrollToTopBtn.classList.remove('visible');
    }
});

// Preloader functionality
window.addEventListener('load', function() {
    const preloader = document.getElementById('preloader');
    setTimeout(() => {
        preloader.style.opacity = '0';
        setTimeout(() => {
            preloader.style.display = 'none';
        }, 500); // Match this with the CSS transition duration
    }, 3000); // 3 seconds delay
});
