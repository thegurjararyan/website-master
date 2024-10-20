// components/gallery/gallery.js
const galleryTrack = document.querySelector('.gallery-track');
const fullviewOverlay = document.getElementById('fullviewOverlay');
const fullviewImage = document.getElementById('fullviewImage');

function setupGalleryItemListeners() {
    const galleryItems = document.querySelectorAll('.gallery-item');
    galleryItems.forEach(item => {
        item.addEventListener('click', function() {
            const imgSrc = this.querySelector('img').src;
            fullviewImage.src = imgSrc;
            fullviewOverlay.classList.add('active');
        });
    });
}

function closeFullview() {
    fullviewOverlay.classList.remove('active');
}

fullviewOverlay.addEventListener('click', function(e) {
    if (e.target === this) {
        closeFullview();
    }
});

// Clone gallery items for seamless loop
const originalItems = document.querySelectorAll('.gallery-item');
const itemWidth = 270; // 250px width + 20px margin
const itemsToClone = originalItems.length;

for (let i = 0; i < itemsToClone; i++) {
    const clone = originalItems[i].cloneNode(true);
    galleryTrack.appendChild(clone);
}

// Set up listeners for all items, including clones
setupGalleryItemListeners();

// Set initial position
let currentPosition = 0;

function moveGallery() {
    currentPosition -= 1; // Adjust speed here
    galleryTrack.style.transform = `translateX(${currentPosition}px)`;

    // Check if we need to reset
    if (currentPosition <= -itemWidth * itemsToClone) {
        currentPosition += itemWidth * itemsToClone;
        galleryTrack.style.transition = 'none';
        galleryTrack.style.transform = `translateX(${currentPosition}px)`;
        void galleryTrack.offsetWidth; // Trigger reflow
        galleryTrack.style.transition = 'transform 0.5s linear';
    }

    requestAnimationFrame(moveGallery);
}

moveGallery();

// GSAP animation for gallery items (if still needed)
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