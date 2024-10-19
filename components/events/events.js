// गुर्जर कार्यक्रम JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // GSAP एनीमेशन
    gsap.registerPlugin(ScrollTrigger);

    const flipCards = document.querySelectorAll('.flip-card');

    flipCards.forEach((card, index) => {
        gsap.from(card, {
            scrollTrigger: {
                trigger: card,
                start: 'top 80%',
                end: 'bottom 20%',
                toggleActions: 'play none none reset',
            },
            opacity: 0,
            y: 50,
            duration: 1.2,
            ease: 'power3.out',
            delay: index * 0.2
        });
    });

    // मोडल फंक्शनैलिटी
    const modal = document.getElementById('eventModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalDescription = document.getElementById('modalDescription');
    const closeBtn = document.getElementsByClassName('close')[0];

    function openModal(title, description) {
        modalTitle.textContent = title;
        modalDescription.textContent = description;
        modal.style.display = 'block';
    }

    function closeModal() {
        modal.style.display = 'none';
    }

    // अधिक जानें बटन के लिए इवेंट लिस्टनर
    const learnMoreButtons = document.querySelectorAll('.btn');
    learnMoreButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const title = this.closest('.flip-card-back').querySelector('h3').textContent;
            const description = this.closest('.flip-card-back').querySelector('p').textContent;
            openModal(title, description);
        });
    });

    // मोडल बंद करने के लिए इवेंट लिस्टनर्स
    closeBtn.addEventListener('click', closeModal);
    window.addEventListener('click', function(e) {
        if (e.target == modal) {
            closeModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
});