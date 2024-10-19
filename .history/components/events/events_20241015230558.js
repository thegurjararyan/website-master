// components/events/events.js
document.addEventListener('DOMContentLoaded', function() {
    const timelineEvents = document.querySelectorAll('.timeline-event');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate');
                if (entry.target.classList.contains('left')) {
                    entry.target.style.transform = 'translateX(-30px)';
                } else {
                    entry.target.style.transform = 'translateX(30px)';
                }
                entry.target.style.opacity = '1';
            }
        });
    }, { threshold: 0.5 });
    
    timelineEvents.forEach(event => {
        observer.observe(event);
    });
});