document.addEventListener('DOMContentLoaded', function() {
    const sponsorsTrack = document.querySelector('.sponsors-track');
    const sponsorItems = document.querySelectorAll('.sponsor-item');
    
    // Clone sponsor items
    sponsorItems.forEach(item => {
        const clone = item.cloneNode(true);
        sponsorsTrack.appendChild(clone);
    });

    // Calculate the total width of all sponsor items
    const totalWidth = Array.from(sponsorItems).reduce((width, item) => {
        return width + item.offsetWidth + parseInt(getComputedStyle(item).marginLeft) * 2;
    }, 0);

    // Set the width of the sponsors track to accommodate all items
    sponsorsTrack.style.width = `${totalWidth * 2}px`;
});

