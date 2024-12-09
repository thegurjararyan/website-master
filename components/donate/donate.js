// GSAP animations
gsap.registerPlugin(ScrollTrigger);

// Split the title text into characters
const titleElement = document.querySelector("#donate-title");
const titleText = titleElement.textContent;
titleElement.innerHTML = titleText.split("").map(char => `<span class="char">${char}</span>`).join("");

// Initial animation
gsap.from(".char", {
  opacity: 0,
  y: 20,
  duration: 0.8,
  ease: "power3.out",
  stagger: 0.03,
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

gsap.from(".payment-icon", { 
  opacity: 0, 
  scale: 0.9, 
  stagger: 0.1, 
  duration: 0.6, 
  ease: "power3.out",
  scrollTrigger: {
    trigger: ".payment-icon",
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

// Subtle floating animation for payment icons
gsap.to(".payment-icon", {
  y: -5,
  duration: 2,
  ease: "sine.inOut",
  stagger: {
    each: 0.2,
    yoyo: true,
    repeat: -1
  }
});
