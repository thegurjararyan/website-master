// Particle.js configuration
particlesJS('particles-js', {
    particles: {
        number: { value: 80, density: { enable: true, value_area: 800 } },
        color: { value: "#ffffff" },
        shape: { type: "circle" },
        opacity: { value: 0.5, random: false },
        size: { value: 3, random: true },
        line_linked: { enable: true, distance: 150, color: "#ffffff", opacity: 0.4, width: 1 },
        move: { enable: true, speed: 6, direction: "none", random: false, straight: false, out_mode: "out", bounce: false }
    },
    interactivity: {
        detect_on: "canvas",
        events: { onhover: { enable: true, mode: "repulse" }, onclick: { enable: true, mode: "push" }, resize: true },
        modes: { repulse: { distance: 100, duration: 0.4 }, push: { particles_nb: 4 } }
    },
    retina_detect: true
});

// GSAP animations
gsap.registerPlugin(ScrollTrigger);

// Animate title
gsap.from("#contact-title", {
    opacity: 0,
    y: -50,
    duration: 1,
    ease: "power3.out",
    scrollTrigger: {
        trigger: "#contact",
        start: "top 80%",
        end: "bottom 20%",
        toggleActions: "play none none reverse"
    }
});

// Animate contact wrapper
gsap.from(".contact-wrapper", {
    opacity: 0,
    scale: 0.9,
    duration: 1,
    ease: "power3.out",
    scrollTrigger: {
        trigger: ".contact-wrapper",
        start: "top 80%",
        end: "bottom 20%",
        toggleActions: "play none none reverse"
    }
});

// Animate info items
gsap.from(".info-item", {
    opacity: 0,
    x: -50,
    stagger: 0.2,
    duration: 0.8,
    ease: "power3.out",
    scrollTrigger: {
        trigger: ".contact-info",
        start: "top 80%",
        end: "bottom 20%",
        toggleActions: "play none none reverse"
    }
});

// Animate social icons
gsap.from(".social-icon", {
    opacity: 0,
    y: 20,
    stagger: 0.1,
    duration: 0.8,
    ease: "power3.out",
    scrollTrigger: {
        trigger: ".social-links",
        start: "top 80%",
        end: "bottom 20%",
        toggleActions: "play none none reverse"
    }
});

// Animate YouTube section
gsap.from(".youtube-section", {
    opacity: 0,
    x: 50,
    duration: 1,
    ease: "power3.out",
    scrollTrigger: {
        trigger: ".youtube-section",
        start: "top 80%",
        end: "bottom 20%",
        toggleActions: "play none none reverse"
    }
});

// Add floating animation to icons
gsap.to(".fas, .fab", {
    y: -5,
    duration: 1.5,
    ease: "sine.inOut",
    stagger: {
        each: 0.2,
        yoyo: true,
        repeat: -1
    }
});
