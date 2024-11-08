document.addEventListener('DOMContentLoaded', function() {
    const sponsorsTrack = document.querySelector('.sponsors-track');
    const sponsorItems = document.querySelectorAll('.sponsor-item');
    
    // Clone all items once to create the infinite effect
    sponsorItems.forEach(item => {
        const clone = item.cloneNode(true);
        sponsorsTrack.appendChild(clone);
    });
});

