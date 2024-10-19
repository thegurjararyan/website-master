// components/events/events.js
function openModal(title, description) {
    const modal = document.getElementById('eventModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalDescription = document.getElementById('modalDescription');
    
    modalTitle.textContent = title;
    modalDescription.textContent = description;
    modal.style.display = 'block';
}

function closeModal() {
    const modal = document.getElementById('eventModal');
    modal.style.display = 'none';
}

// Close modal when clicking outside of it
window.onclick = function(event) {
    const modal = document.getElementById('eventModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}

// GSAP animations
gsap.registerPlugin(ScrollTrigger);

gsap.utils.toArray('.timeline-item').forEach((item, i) => {
    ScrollTrigger.create({
        trigger: item,
        start: 'top bottom-=100',
        toggleClass: 'active',
        once: true
    });
});