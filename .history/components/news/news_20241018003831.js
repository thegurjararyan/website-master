let currentIndex = 0;
const carousel = document.querySelector('.news-carousel');
const cards = document.querySelectorAll('.news-card');
const totalCards = cards.length;

function getVisibleCards() {
    return window.innerWidth < 769 ? 1 : 3;
}

function showSlide(index) {
    const visibleCards = getVisibleCards();
    const offset = -index * (100 / visibleCards);
    carousel.style.transform = `translateX(${offset}%)`;
}

function moveSlide(direction) {
    const visibleCards = getVisibleCards();
    const maxIndex = totalCards - visibleCards;

    if (direction > 0) { // Moving right
        currentIndex = currentIndex + visibleCards > maxIndex ? 0 : currentIndex + visibleCards;
    } else { // Moving left
        currentIndex = currentIndex - visibleCards < 0 ? maxIndex : currentIndex - visibleCards;
    }

    showSlide(currentIndex);
}

function initCarousel() {
    const visibleCards = getVisibleCards();
    cards.forEach((card, index) => {
        card.style.flex = `0 0 ${100 / visibleCards}%`;
        card.style.maxWidth = `${100 / visibleCards}%`;
    });
    showSlide(currentIndex);
}

window.addEventListener('resize', initCarousel);
initCarousel();

// Add event listeners to arrows
document.querySelector('.news-carousel-arrow.left').addEventListener('click', () => moveSlide(-1));
document.querySelector('.news-carousel-arrow.right').addEventListener('click', () => moveSlide(1));