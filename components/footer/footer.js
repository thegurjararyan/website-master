// Footer animations
const footerAnimations = () => {
    // Animate footer sections
    gsap.from(".footer-left", {
        opacity: 0,
        y: 30,
        duration: 0.8,
        ease: "power3.out",
        scrollTrigger: {
            trigger: ".footer",
            start: "top 90%"
        }
    });

    gsap.from(".footer-right", {
        opacity: 0,
        y: 30,
        duration: 0.8,
        delay: 0.2,
        ease: "power3.out",
        scrollTrigger: {
            trigger: ".footer",
            start: "top 90%"
        }
    });

    // Animate footer links
    gsap.from(".footer-nav a, .event-links a, .join-links a", {
        opacity: 0,
        x: -20,
        stagger: 0.05,
        duration: 0.5,
        ease: "power3.out",
        scrollTrigger: {
            trigger: ".footer-right",
            start: "top 90%"
        }
    });

    // Animate social icons in footer
    gsap.from(".footer-social-links .social-icon", {
        opacity: 0,
        scale: 0,
        stagger: 0.1,
        duration: 0.5,
        ease: "back.out(1.7)",
        scrollTrigger: {
            trigger: ".footer-social-links",
            start: "top 95%"
        }
    });
};

// Initialize footer animations
document.addEventListener('DOMContentLoaded', footerAnimations);

// Smooth scroll for navigation links
document.querySelectorAll('.nav-links a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});