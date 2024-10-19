// components/sponsors/sponsors.js
document.addEventListener('DOMContentLoaded', function() {
    gsap.registerPlugin(ScrollTrigger);

    const sponsorItems = document.querySelectorAll('.sponsor-item');

    // Reveal animation
    gsap.from(sponsorItems, {
        y: 50,
        opacity: 0,
        duration: 0.8,
        stagger: 0.1,
        ease: "power3.out",
        scrollTrigger: {
            trigger: '.sponsors-grid',
            start: 'top 80%',
        }
    });

    // Continuous subtle animation
    sponsorItems.forEach((item, index) => {
        gsap.to(item, {
            y: "+=5",
            duration: 2,
            repeat: -1,
            yoyo: true,
            ease: "sine.inOut",
            delay: index * 0.2
        });
    });
});