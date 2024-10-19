gsap.registerPlugin(ScrollTrigger);

document.addEventListener('DOMContentLoaded', function() {
    const timelineEvents = gsap.utils.toArray('.timeline-event');
    const timelineProgress = document.querySelector('.timeline-progress');
    
    gsap.to(timelineProgress, {
        height: '100%',
        ease: 'none',
        scrollTrigger: {
            trigger: '.timeline',
            start: 'top center',
            end: 'bottom center',
            scrub: true
        }
    });

    timelineEvents.forEach((event, index) => {
        const content = event.querySelector('.timeline-content');
        const isLeft = event.classList.contains('left');
        
        gsap.from(content, {
            x: isLeft ? -50 : 50,
            opacity: 0,
            duration: 1,
            scrollTrigger: {
                trigger: event,
                start: 'top 80%',
                end: 'top 50%',
                scrub: 1,
                onEnter: () => event.classList.add('animate')
            }
        });
    });

    // Modal functionality
    const modal = document.getElementById('event-modal');
    const modalTitle = document.getElementById('modal-title');
    const modalDescription = document.getElementById('modal-description');
    const modalClose = document.getElementById('modal-close');

    document.querySelectorAll('.event-button').forEach(button => {
        button.addEventListener('click', () => {
            const content = button.closest('.timeline-content');
            const title = content.querySelector('.event-title').textContent;
            const description = content.querySelector('.event-description').textContent;

            modalTitle.textContent = title;
            modalDescription.textContent = description;

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