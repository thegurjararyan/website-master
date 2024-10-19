const newsCardsContainer = document.getElementById('news-cards-container');
const prevNews = document.getElementById('prev-news');
const nextNews = document.getElementById('next-news');

let currentIndex = 0;

prevNews.addEventListener('click', () => {
    if (currentIndex > 0) {
        currentIndex--;
        updateCarousel();
    }
});

nextNews.addEventListener('click', () => {
    if (currentIndex < newsCardsContainer.children.length - 1) {
        currentIndex++;
        updateCarousel();
    }
});

function updateCarousel() {
    const cardWidth = newsCardsContainer.children[0].offsetWidth;
    newsCardsContainer.style.transform = `translateX(-${currentIndex * (cardWidth + 20)}px)`;
}