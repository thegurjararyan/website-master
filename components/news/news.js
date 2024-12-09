let currentIndex = 0;
const carousel = document.getElementById('newsCarousel');
const cards = carousel.children;

function updateCarousel() {
    const cardWidth = cards[0].offsetWidth;
    carousel.style.transform = `translateX(-${currentIndex * cardWidth}px)`;
}

function moveCarousel(direction) {
    currentIndex += direction;
    if (currentIndex < 0) {
        currentIndex = cards.length - 1;
    } else if (currentIndex >= cards.length) {
        currentIndex = 0;
    }
    updateCarousel();
}

// Initial update
updateCarousel();

// Update on window resize
window.addEventListener('resize', updateCarousel);