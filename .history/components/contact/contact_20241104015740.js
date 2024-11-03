// Contact section animations
gsap.registerPlugin(ScrollTrigger);

// Animate contact section elements
const contactAnimations = () => {
    // Animate logo
    gsap.from(".logo-animation", {
        opacity: 0,
        y: -30,
        duration: 1,
        ease: "power3.out",
        scrollTrigger: {
            trigger: ".contact-section",
            start: "top 80%"
        }
    });

    // Animate text elements
    gsap.from(".animate-text", {
        opacity: 0,
        y: 30,
        stagger: 0.2,
        duration: 0.8,
        ease: "power3.out",
        scrollTrigger: {
            trigger: ".contact-section",
            start: "top 80%"
        }
    });

    // Animate social icons
    gsap.from(".social-icon", {
        opacity: 0,
        scale: 0,
        stagger: 0.1,
        duration: 0.5,
        ease: "back.out(1.7)",
        scrollTrigger: {
            trigger: ".social-links",
            start: "top 90%"
        }
    });

    // Animate YouTube section
    gsap.from(".youtube-showcase", {
        opacity: 0,
        x: 50,
        duration: 1,
        ease: "power3.out",
        scrollTrigger: {
            trigger: ".youtube-showcase",
            start: "top 80%"
        }
    });
};

// Initialize animations
document.addEventListener('DOMContentLoaded', contactAnimations);