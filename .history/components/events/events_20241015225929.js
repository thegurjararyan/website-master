document.addEventListener('DOMContentLoaded', function() {
    const track = document.querySelector('.timeline-track');
    const events = document.querySelectorAll('.timeline-event');
    let currentIndex = 0;

    gsap.to(events[currentIndex], { scale: 1, opacity: 1, duration: 0.5 });

    function updateTimeline() {
        gsap.to(track, {
            x: -currentIndex * 400,
            duration: 0.5,
            ease: "power2.out"
        });

        events.forEach((event, index) => {
            if (index === currentIndex) {
                gsap.to(event, { scale: 1, opacity: 1, duration: 0.5 });
            } else {
                gsap.to(event, { scale: 0.9, opacity: 0.7, duration: 0.5 });
            }
        });
    }

    function nextEvent() {
        if (currentIndex < events.length - 1) {
            currentIndex++;
            updateTimeline();
        }
    }

    function prevEvent() {
        if (currentIndex > 0) {
            currentIndex--;
            updateTimeline();
        }
    }

    // Add navigation buttons
    const nextBtn = document.createElement('button');
    nextBtn.textContent = 'Next';
    nextBtn.addEventListener('click', nextEvent);

    const prevBtn = document.createElement('button');
    prevBtn.textContent = 'Prev';
    prevBtn.addEventListener('click', prevEvent);

    document.querySelector('.timeline-container').appendChild(prevBtn);
    document.querySelector('.timeline-container').appendChild(nextBtn);

    // Optional: Add keyboard navigation
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowRight') nextEvent();
        if (e.key === 'ArrowLeft') prevEvent();
    });
});