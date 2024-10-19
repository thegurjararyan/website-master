document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('news-cards-container');
    const prevBtn = document.getElementById('prev-news');
    const nextBtn = document.getElementById('next-news');
    const cards = container.querySelectorAll('.news-card');
    let currentIndex = 0;

    function updateCarousel() {
        const cardWidth = cards[0].offsetWidth;
        container.style.transform = `translateX(-${currentIndex * cardWidth}px)`;
        
        // Update button visibility
        prevBtn.style.display = currentIndex > 0 ? 'flex' : 'none';
        nextBtn.style.display = currentIndex < cards.length - 1 ? 'flex' : 'none';
    }

    function moveCarousel(direction) {
        if (direction === 'next' && currentIndex < cards.length - 1) {
            currentIndex++;
        } else if (direction === 'prev' && currentIndex > 0) {
            currentIndex--;
        }
        updateCarousel();
    }

    prevBtn.addEventListener('click', () => moveCarousel('prev'));
    nextBtn.addEventListener('click', () => moveCarousel('next'));

    // Initial update
    updateCarousel();

    // Recalculate on window resize
    window.addEventListener('resize', updateCarousel);
});