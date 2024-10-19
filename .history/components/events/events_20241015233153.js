document.addEventListener('DOMContentLoaded', function() {
    const timelineEvents = document.querySelectorAll('.timeline-event');
    const timelineProgress = document.querySelector('.timeline-progress');
    
    function animateTimeline() {
        const scrollPercentage = (window.scrollY / (document.documentElement.scrollHeight - window.innerHeight)) * 100;
        timelineProgress.style.height = `${scrollPercentage}%`;

        timelineEvents.forEach((event) => {
            const eventTop = event.getBoundingClientRect().top;
            if (eventTop < window.innerHeight * 0.8) {
                event.classList.add('animate');
                event.style.transform = 'translateX(0)';
            }
        });
    }

    window.addEventListener('scroll', animateTimeline);
    animateTimeline(); // Initial call to set initial state

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

            modal.style.display = 'flex';
            setTimeout(() => {
                modal.style.opacity = '1';
            }, 10);
        });
    });

    modalClose.addEventListener('click', () => {
        modal.style.opacity = '0';
        setTimeout(() => {
            modal.style.display = 'none';
        }, 300);
    });
});