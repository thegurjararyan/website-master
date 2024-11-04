// components/gallery/gallery.js
const galleryTrack = document.querySelector('.gallery-track');
const fullviewOverlay = document.getElementById('fullviewOverlay');
const fullviewImage = document.getElementById('fullviewImage');

// Keep track of gallery state
let currentImageIndex = 0;
const galleryImages = [];

function setupGalleryItemListeners() {
    const galleryItems = document.querySelectorAll('.gallery-item');
    
    // Store original images (not clones) in galleryImages array
    galleryItems.forEach((item, index) => {
        if (index < itemsToClone) { // Only store original images
            galleryImages.push(item.querySelector('img').src);
        }
        
        item.addEventListener('click', function() {
            const clickedIndex = Array.from(galleryItems).indexOf(this) % itemsToClone;
            currentImageIndex = clickedIndex;
            showImage(currentImageIndex);
            fullviewOverlay.classList.add('active');
            // Pause the gallery animation when viewing full image
            galleryTrack.style.animationPlayState = 'paused';
        });
    });
}

function showImage(index) {
    fullviewImage.src = galleryImages[index];
}

function showNextImage() {
    currentImageIndex = (currentImageIndex + 1) % galleryImages.length;
    showImage(currentImageIndex);
}

function showPrevImage() {
    currentImageIndex = (currentImageIndex - 1 + galleryImages.length) % galleryImages.length;
    showImage(currentImageIndex);
}

function closeFullview() {
    fullviewOverlay.classList.remove('active');
    // Resume the gallery animation
    galleryTrack.style.animationPlayState = 'running';
}

// Set up navigation buttons
document.getElementById('nextBtn').addEventListener('click', showNextImage);
document.getElementById('prevBtn').addEventListener('click', showPrevImage);
document.getElementById('exitBtn').addEventListener('click', closeFullview);

// Add keyboard navigation
document.addEventListener('keydown', function(e) {
    if (!fullviewOverlay.classList.contains('active')) return;
    
    switch(e.key) {
        case 'ArrowRight':
            showNextImage();
            break;
        case 'ArrowLeft':
            showPrevImage();
            break;
        case 'Escape':
            closeFullview();
            break;
    }
});

// Clone gallery items for seamless loop
const originalItems = document.querySelectorAll('.gallery-item');
const itemWidth = window.innerWidth < 768 ? 160 : 270;
const itemsToClone = originalItems.length;

for (let i = 0; i < itemsToClone; i++) {
    const clone = originalItems[i].cloneNode(true);
    galleryTrack.appendChild(clone);
}

// Set up listeners for all items, including clones
setupGalleryItemListeners();

// Animation code
let currentPosition = 0;

function moveGallery() {
    if (fullviewOverlay.classList.contains('active')) {
        requestAnimationFrame(moveGallery);
        return;
    }
    
    currentPosition -= 1;
    galleryTrack.style.transform = `translateX(${currentPosition}px)`;

    if (currentPosition <= -itemWidth * itemsToClone) {
        currentPosition += itemWidth * itemsToClone;
        galleryTrack.style.transition = 'none';
        galleryTrack.style.transform = `translateX(${currentPosition}px)`;
        void galleryTrack.offsetWidth;
        galleryTrack.style.transition = 'transform 0.5s linear';
    }

    requestAnimationFrame(moveGallery);
}

moveGallery();

// Keep your existing resize event listener
window.addEventListener('resize', function() {
    itemWidth = window.innerWidth < 768 ? 160 : 270;
});

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
