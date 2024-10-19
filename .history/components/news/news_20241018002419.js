let currentIndex = 0;
const totalSlides = document.querySelectorAll('.news-card').length;
const carousel = document.querySelector('.news-carousel');

// Function to show a specific slide
function showSlide(index) {
    const offset = -index * 100; // Move by one card's width (100% for one card)
    carousel.style.transform = `translateX(${offset}%)`;
}

// Function to move the slide in the specified direction
function moveSlide(direction) {
    // Update index based on direction
    currentIndex += direction;
    
    // Looping logic
    if (currentIndex < 0) {
        currentIndex = totalSlides - 1; // Go to last card
    } else if (currentIndex >= totalSlides) {
        currentIndex = 0; // Go back to first card
    }
    
    showSlide(currentIndex);
}

// Show the initial slide
showSlide(currentIndex);
