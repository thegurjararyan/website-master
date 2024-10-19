document.addEventListener('DOMContentLoaded', function() {
    const timelineEvents = document.querySelectorAll('.timeline-event');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate');
            }
        });
    }, { threshold: 0.5 });
    
    timelineEvents.forEach(event => {
        observer.observe(event);
    });
});