document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('news-cards-container');
    const prevBtn = document.getElementById('prev-news');
    const nextBtn = document.getElementById('next-news');
    const cards = container.querySelectorAll('.news-card');
    let currentIndex = 0;

    function showCard(index) {
        container.style.transform = `translateX(-${index * 100}%)`;
    }

    function nextCard() {
        currentIndex = (currentIndex + 1) % cards.length;
        showCard(currentIndex);
    }

    function prevCard() {
        currentIndex = (currentIndex - 1 + cards.length) % cards.length;
        showCard(currentIndex);
    }

    nextBtn.addEventListener('click', nextCard);
    prevBtn.addEventListener('click', prevCard);

    // Initialize
    showCard(currentIndex);
});