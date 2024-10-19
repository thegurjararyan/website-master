// components/about/about.js
function animateNumber(element, start, end, duration) {
    let startTime = null;
    const animation = (currentTime) => {
        if (!startTime) startTime = currentTime;
        const progress = Math.min((currentTime - startTime) / duration, 1);
        const current = Math.floor(progress * (end - start) + start);
        element.textContent = current.toLocaleString();
        if (progress < 1) {
            requestAnimationFrame(animation);
        }
    };
    requestAnimationFrame(animation);
}

function isElementInViewport(el) {
    const rect = el.getBoundingClientRect();
    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
}

function handleScroll() {
    const statsContainer = document.querySelector('.stats-container');
    if (isElementInViewport(statsContainer)) {
        animateNumber(document.getElementById('yearsCount'), 0, 15, 2000);
        animateNumber(document.getElementById('eventsCount'), 0, 100, 2000);
        animateNumber(document.getElementById('artistsCount'), 0, 500, 2000);
        animateNumber(document.getElementById('membersCount'), 0, 10000, 2000);
        window.removeEventListener('scroll', handleScroll);
    }
}

window.addEventListener('scroll', handleScroll);
window.addEventListener('load', handleScroll);

// GSAP animations
gsap.registerPlugin(ScrollTrigger);

gsap.from(".stats-container", {
    scrollTrigger: {
        trigger: ".stats-container",
        start: "top 80%",
    },
    y: 50,
    opacity: 0,
    duration: 1,
    ease: "power3.out"
});

gsap.from(".stat-item", {
    scrollTrigger: {
        trigger: ".stats-container",
        start: "top 80%",
    },
    y: 30,
    opacity: 0,
    duration: 0.8,
    stagger: 0.2,
    ease: "power2.out"
});

// About section heading animation
gsap.from(".about-heading", {
    scrollTrigger: {
        trigger: ".about-heading",
        start: "top 80%",
    },
    y: 50,
    opacity: 0,
    duration: 1,
    ease: "power3.out"
});

// Founder section animation
gsap.registerPlugin(ScrollTrigger);

gsap.to(".founder-section", {
    scrollTrigger: {
        trigger: ".founder-section",
        start: "top 80%",
    },
    opacity: 1,
    y: 0,
    duration: 1,
    ease: "power3.out"
});

gsap.to(".founder-info", {
    scrollTrigger: {
        trigger: ".founder-section",
        start: "top 70%",
    },
    opacity: 1,
    y: 0,
    duration: 1,
    delay: 0.3,
    ease: "power3.out"
});

gsap.to(".founder-image", {
    scrollTrigger: {
        trigger: ".founder-section",
        start: "top 70%",
    },
    opacity: 1,
    y: 0,
    duration: 1,
    delay: 0.6,
    ease: "power3.out"
});