document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('news-cards-container');
    const prevBtn = document.getElementById('prev-news');
    const nextBtn = document.getElementById('next-news');
    const cards = container.querySelectorAll('.news-card');
    let currentIndex = 0;

    function updateCarousel() {
        const cardWidth = cards[0].offsetWidth;
        container.style.transform = `translateX(-${currentIndex * cardWidth}px)`;
        console.log('Current index:', currentIndex);
    }

    function moveCarousel(direction) {
        if (direction === 'next') {
            currentIndex = (currentIndex + 1) % cards.length;
        } else if (direction === 'prev') {
            currentIndex = (currentIndex - 1 + cards.length) % cards.length;
        }
        updateCarousel();
    }

    nextBtn.addEventListener('click', () => {
        console.log('Next button clicked');
        moveCarousel('next');
    });

    prevBtn.addEventListener('click', () => {
        console.log('Prev button clicked');
        moveCarousel('prev');
    });

    // Initial update
    updateCarousel();

    // Recalculate on window resize
    window.addEventListener('resize', updateCarousel);
});