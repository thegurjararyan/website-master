// components/sponsors/sponsors.js
document.addEventListener('DOMContentLoaded', () => {
    const sponsorImages = document.querySelectorAll('.sponsors-slide img');

    sponsorImages.forEach(img => {
        img.addEventListener('click', () => {
            const sponsorName = img.alt;
            console.log(`Clicked on ${sponsorName}`);
            // Add your logic here (e.g., open sponsor's website)
        });
    });
});