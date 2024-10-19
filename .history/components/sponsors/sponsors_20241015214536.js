// components/sponsors/sponsors.js
document.addEventListener('DOMContentLoaded', () => {
    const carousel = document.querySelector('.sponsors-carousel');
    const items = document.querySelectorAll('.sponsor-item');
    const itemCount = items.length;
    let angle = 0;
    const radius = 400; // Adjust this value to change the size of the cylinder

    function positionItems() {
        items.forEach((item, index) => {
            const theta = (index / itemCount) * 2 * Math.PI;
            const x = Math.sin(theta) * radius;
            const z = Math.cos(theta) * radius;
            item.style.transform = `translateX(${x}px) translateZ(${z}px) rotateY(${-theta}rad)`;
        });
    }

    function rotateCarousel() {
        angle += 0.01;
        carousel.style.transform = `rotateY(${angle}rad)`;
        requestAnimationFrame(rotateCarousel);
    }

    positionItems();
    rotateCarousel();

    // Pause rotation on hover
    carousel.addEventListener('mouseenter', () => {
        carousel.style.animationPlayState = 'paused';
    });

    carousel.addEventListener('mouseleave', () => {
        carousel.style.animationPlayState = 'running';
    });

    // Click event for sponsors
    items.forEach(item => {
        item.addEventListener('click', () => {
            const sponsorName = item.querySelector('img').alt;
            console.log(`Clicked on ${sponsorName}`);
            // Add your logic here (e.g., open sponsor's website)
        });
    });
});