gsap.registerPlugin(ScrollTrigger);

document.addEventListener('DOMContentLoaded', function() {
    const timelineEvents = gsap.utils.toArray('.timeline-event');
    
    timelineEvents.forEach((event, index) => {
        const content = event.querySelector('.timeline-content');
        const isLeft = event.classList.contains('left');
        
        gsap.set(content, { 
            x: isLeft ? -100 : 100, 
            opacity: 0,
        });
        
        gsap.to(content, {
            x: 0,
            opacity: 1,
            duration: 1,
            scrollTrigger: {
                trigger: event,
                start: 'top 80%',
                end: 'top 50%',
                scrub: 1,
            }
        });
    });

    // Modal functionality
    const modal = document.getElementById('event-modal');
    const modalTitle = document.getElementById('modal-title');
    const modalDescription = document.getElementById('modal-description');
    const modalClose = document.getElementById('modal-close');
    const modalLink = document.getElementById('modal-link');

    timelineEvents.forEach(event => {
        event.addEventListener('click', () => {
            const title = event.querySelector('.event-title').textContent;
            const description = event.querySelector('.event-description').textContent;
            const url = event.dataset.eventUrl;

            modalTitle.textContent = title;
            modalDescription.textContent = description;
            modalLink.href = url;

            gsap.to(modal, {
                display: 'flex',
                opacity: 1,
                duration: 0.3,
                ease: 'power2.inOut'
            });
        });
    });

    modalClose.addEventListener('click', () => {
        gsap.to(modal, {
            opacity: 0,
            duration: 0.3,
            ease: 'power2.inOut',
            onComplete: () => {
                modal.style.display = 'none';
            }
        });
    });
});