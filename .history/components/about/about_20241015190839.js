// components/about/about.js
function animateNumber(element, start, end, duration, postfix = '') {
    let current = start;
    const range = end - start;
    const increment = end > start ? 1 : -1;
    const stepTime = Math.abs(Math.floor(duration / range));
    const timer = setInterval(() => {
        current += increment;
        element.textContent = current.toLocaleString() + postfix;
        if (current == end) {
            clearInterval(timer);
        }
    }, stepTime);
}

function animateBar(element, targetWidth) {
    gsap.to(element, {
        width: targetWidth + '%',
        duration: 2,
        ease: 'power2.out'
    });
}

ScrollTrigger.create({
    trigger: ".stats-container",
    start: "top 80%",
    onEnter: () => {
        animateNumber(document.getElementById('peopleConnected'), 0, 18, 2000, ' Crore');
        animateNumber(document.getElementById('statesJoined'), 0, 13, 1500);
        animateBar(document.getElementById('peopleConnectedBar'), 100);
        animateBar(document.getElementById('statesJoinedBar'), (13 / 29) * 100); // Assuming 29 states in India
    },
    once: true
});

gsap.from(".stat-item", {
    scrollTrigger: {
        trigger: ".stats-container",
        start: "top 80%",
    },
    y: 50,
    opacity: 0,
    duration: 1,
    stagger: 0.3,
    ease: "power3.out"
});