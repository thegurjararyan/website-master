// components/home/home.js
const introSection = document.getElementById('intro-section');
introSection.addEventListener('mousemove', function(e) {
    const ripple = document.createElement('div');
    ripple.className = 'ripple';
    ripple.style.left = `${e.clientX - introSection.offsetLeft}px`;
    ripple.style.top = `${e.clientY - introSection.offsetTop}px`;
    introSection.appendChild(ripple);
    setTimeout(() => {
        ripple.remove();
    }, 600);
});