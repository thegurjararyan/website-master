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
        nextBtn.style.display = currentIndex < cards.length - getVisibleCards() ? 'flex' : 'none';
    }

    function getVisibleCards() {
        if (window.innerWidth >= 1024) return 3;
        if (window.innerWidth >= 768) return 2;
        return 1;
    }

    function moveCarousel(direction) {
        const visibleCards = getVisibleCards();
        if (direction === 'next' && currentIndex < cards.length - visibleCards) {
            currentIndex++;
        } else if (direction === 'prev' && currentIndex > 0) {
            currentIndex--;
        }
        updateCarousel();
    }

    prevBtn.addEventListener('click', () => moveCarousel('prev'));
    nextBtn.addEventListener('click', () => moveCarousel('next'));

    window.addEventListener('resize', () => {
        currentIndex = 0; // Reset to first slide on resize
        updateCarousel();
    });

    // Initial update
    updateCarousel();
});