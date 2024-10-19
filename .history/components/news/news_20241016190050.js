const newsCardsContainer = document.getElementById('news-cards-container');
const prevNews = document.getElementById('prev-news');
const nextNews = document.getElementById('next-news');

let currentIndex = 0;

prevNews.addEventListener('click', () => {
    currentIndex = (currentIndex > 0) ? currentIndex - 1 : newsCardsContainer.children.length - 1;
    updateCarousel();
});

nextNews.addEventListener('click', () => {
    currentIndex = (currentIndex < newsCardsContainer.children.length - 1) ? currentIndex + 1 : 0;
    updateCarousel();
});

function updateCarousel() {
    const cardWidth = newsCardsContainer.children[0].offsetWidth;
    newsCardsContainer.style.transform = `translateX(-${currentIndex * (cardWidth + 20)}px)`;
}