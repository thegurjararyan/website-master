// components/events/events.js
gsap.registerPlugin(ScrollTrigger);

document.addEventListener('DOMContentLoaded', function() {
    const timelineEvents = gsap.utils.toArray('.timeline-event');
    
    timelineEvents.forEach((event, index) => {
        const content = event.querySelector('.timeline-content');
        const isLeft = event.classList.contains('left');
        
        gsap.set(content, { 
            x: isLeft ? 50 : -50, 
            opacity: 0,
            filter: 'blur(4px)'
        });
        
        gsap.to(content, {
            x: 0,
            opacity: 1,
            filter: 'blur(0px)',
            duration: 1,
            scrollTrigger: {
                trigger: event,
                start: 'top 80%',
                end: 'top 50%',
                scrub: 1,
            }
        });
        
        gsap.to(content.querySelector('::after'), {
            width: 50,
            duration: 1,
            scrollTrigger: {
                trigger: event,
                start: 'top 80%',
                end: 'top 50%',
                scrub: 1,
            }
        });
    });
});