// GSAP animations
gsap.registerPlugin(ScrollTrigger);

// Particle.js configuration
particlesJS('particles-js', {
  particles: {
    number: { value: 80, density: { enable: true, value_area: 800 } },
    color: { value: "#ffffff" },
    shape: { type: "circle" },
    opacity: { value: 0.5, random: true, anim: { enable: true, speed: 1, opacity_min: 0.1, sync: false } },
    size: { value: 3, random: true, anim: { enable: false, speed: 40, size_min: 0.1, sync: false } },
    line_linked: { enable: true, distance: 150, color: "#ffffff", opacity: 0.4, width: 1 },
    move: { enable: true, speed: 6, direction: "none", random: false, straight: false, out_mode: "out", bounce: false }
  },
  interactivity: {
    detect_on: "canvas",
    events: { onhover: { enable: true, mode: "repulse" }, onclick: { enable: true, mode: "push" }, resize: true },
    modes: { grab: { distance: 400, line_linked: { opacity: 1 } }, bubble: { distance: 400, size: 40, duration: 2, opacity: 8, speed: 3 }, repulse: { distance: 200, duration: 0.4 }, push: { particles_nb: 4 }, remove: { particles_nb: 2 } }
  },
  retina_detect: true
});

// Split the title text into words
const titleElement = document.querySelector("#donate-title");
const titleText = titleElement.textContent;
titleElement.innerHTML = titleText.split(" ").map(word => `<span class="word">${word}</span>`).join(" ");

// Initial animations
gsap.from(".word", {
  opacity: 0,
  y: 20,
  duration: 0.8,
  ease: "power3.out",
  stagger: 0.1,
});

gsap.from("#donate-description", { 
  opacity: 0, 
  y: 20, 
  duration: 0.8, 
  ease: "power3.out",
  scrollTrigger: {
    trigger: "#donate-description",
    start: "top 80%",
    toggleActions: "play none none reverse"
  }
});

gsap.from(".payment-method", { 
  opacity: 0, 
  scale: 0.9, 
  stagger: 0.1, 
  duration: 0.6, 
  ease: "power3.out",
  scrollTrigger: {
    trigger: ".payment-method",
    start: "top 80%",
    toggleActions: "play none none reverse"
  }
});

gsap.from("#donate-button", { 
  opacity: 0, 
  y: 20, 
  duration: 0.8, 
  ease: "power3.out",
  scrollTrigger: {
    trigger: "#donate-button",
    start: "top 80%",
    toggleActions: "play none none reverse"
  }
});

// Hover animation for the donate button
const donateButton = document.querySelector("#donate-button");
donateButton.addEventListener("mouseenter", () => gsap.to("#donate-button", { scale: 1.05, duration: 0.3 }));
donateButton.addEventListener("mouseleave", () => gsap.to("#donate-button", { scale: 1, duration: 0.3 }));

// Subtle floating animation for payment methods
gsap.to(".payment-method", {
  y: -5,
  duration: 2,
  ease: "sine.inOut",
  stagger: {
    each: 0.2,
    yoyo: true,
    repeat: -1
  }
});
