document.addEventListener('DOMContentLoaded', function() {
    const timelineEvents = document.querySelectorAll('.timeline-event');
    
    function checkScroll() {
        timelineEvents.forEach(event => {
            const eventTop = event.getBoundingClientRect().top;
            const windowHeight = window.innerHeight;
            
            if (eventTop < windowHeight * 0.75) {
                event.classList.add('animate');
            }
        });
    }
    
    window.addEventListener('scroll', checkScroll);
    checkScroll(); // Check on initial load
});