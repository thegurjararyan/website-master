// components/sponsors/sponsors.js
document.addEventListener('DOMContentLoaded', () => {
    const sponsorImages = document.querySelectorAll('.sponsors-track img');

    sponsorImages.forEach(img => {
        img.addEventListener('mouseenter', () => {
            gsap.to(img, { scale: 1.1, duration: 0.3, ease: 'power2.out' });
        });

        img.addEventListener('mouseleave', () => {
            gsap.to(img, { scale: 1, duration: 0.3, ease: 'power2.out' });
        });

        img.addEventListener('click', () => {
            // Add functionality to open sponsor's website or show more info
            console.log('Sponsor clicked:', img.alt);
        });
    });
});