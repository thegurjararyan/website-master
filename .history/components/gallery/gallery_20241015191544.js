// components/gallery/gallery.js
const galleryItems = document.querySelectorAll('.gallery-item');
const fullviewOverlay = document.getElementById('fullviewOverlay');
const fullviewImage = document.getElementById('fullviewImage');

galleryItems.forEach(item => {
    item.addEventListener('click', function() {
        const imgSrc = this.querySelector('img').src;
        fullviewImage.src = imgSrc;
        fullviewOverlay.classList.add('active');
    });
});

function closeFullview() {
    fullviewOverlay.classList.remove('active');
}

fullviewOverlay.addEventListener('click', function(e) {
    if (e.target === this) {
        closeFullview();
    }
});

// GSAP animation for gallery items
gsap.utils.toArray('.gallery-item').forEach((item, i) => {
    gsap.from(item, {
        scrollTrigger: {
            trigger: item,
            start: 'top bottom-=100',
            toggleActions: 'play none none reverse'
        },
        opacity: 0,
        y: 50,
        duration: 0.8,
        ease: 'power3.out',
        delay: i * 0.1
    });
});