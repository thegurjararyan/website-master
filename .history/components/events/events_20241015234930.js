document.addEventListener('DOMContentLoaded', function() {
    gsap.registerPlugin(ScrollTrigger);

    const timelineItems = document.querySelectorAll('.timeline-item');

    timelineItems.forEach((item, index) => {
        gsap.from(item, {
            scrollTrigger: {
                trigger: item,
                start: 'top 80%',
                end: 'bottom 20%',
                toggleActions: 'play none none reset',
                markers: false // Set to true if you want to debug
            },
            opacity: 0,
            y: 50,
            duration: 1.2,
            ease: 'power3.out',
            delay: index * 0.2 // Stagger the animations
        });
    });

    // Modal Functionality
    const eventModal = document.getElementById('eventModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalDescription = document.getElementById('modalDescription');

    function openModal(title, description) {
        modalTitle.textContent = title;
        modalDescription.textContent = description;
        eventModal.classList.add('active');
    }

    function closeModal() {
        eventModal.classList.remove('active');
    }

    eventModal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
});