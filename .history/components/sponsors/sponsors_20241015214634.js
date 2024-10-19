// components/sponsors/sponsors.js
gsap.registerPlugin(ScrollTrigger);

const sponsorItems = gsap.utils.toArray('.sponsor-item');

// Fade in animation
gsap.from(sponsorItems, {
    scrollTrigger: {
        trigger: ".sponsors-grid",
        start: "top 80%",
    },
    opacity: 0,
    y: 20,
    duration: 0.8,
    stagger: {
        amount: 1,
        grid: "auto",
        from: "center"
    },
    ease: "power3.out",
});

// Subtle pulse animation
sponsorItems.forEach((item, index) => {
    gsap.to(item, {
        scale: 1.05,
        duration: 1,
        repeat: -1,
        yoyo: true,
        ease: "sine.inOut",
        delay: index * 0.1,
    });
});

// Click event for sponsors
sponsorItems.forEach(item => {
    item.addEventListener('click', () => {
        const sponsorName = item.querySelector('img').alt;
        console.log(`Clicked on ${sponsorName}`);
        // Add your logic here (e.g., open sponsor's website)
    });
});