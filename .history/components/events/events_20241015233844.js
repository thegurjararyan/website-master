document.addEventListener('DOMContentLoaded', function() {
    const timelineItems = document.querySelectorAll('.timeline-item');

    timelineItems.forEach((item, index) => {
        gsap.from(item, {
            scrollTrigger: {
                trigger: item,
                start: 'top 80%',
                toggleActions: 'play none none reset'
            },
            opacity: 0,
            x: index % 2 === 0 ? -200 : 200,
            duration: 1.2,
            ease: 'power3.out'
        });

        gsap.from(item.querySelector('.content'), {
            scrollTrigger: {
                trigger: item,
                start: 'top 80%',
            },
            opacity: 0,
            y: 50,
            duration: 1,
            delay: 0.3,
            ease: 'power3.out'
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