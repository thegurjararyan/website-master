// GSAP animations
gsap.registerPlugin(ScrollTrigger);

// Split the title text into characters
const titleElement = document.querySelector("#donate-title");
const titleText = titleElement.textContent;
titleElement.innerHTML = titleText.split("").map(char => `<span class="char">${char}</span>`).join("");

// Initial animation
gsap.from(".char", {
  opacity: 0,
  y: 50,
  rotateX: -90,
  stagger: 0.05,
  duration: 1,
  ease: "back.out(1.7)",
  onComplete: () => {
    // Start the continuous animation after the initial animation
    gsap.to(".char", {
      y: -5,
      duration: 1.5,
      ease: "sine.inOut",
      stagger: {
        each: 0.1,
        repeat: -1,
        yoyo: true
      }
    });
  }
});

gsap.from("#donate-description", { 
  opacity: 0, 
  y: 50, 
  duration: 1, 
  ease: "power3.out",
  scrollTrigger: {
    trigger: "#donate-description",
    start: "top 80%",
    end: "bottom 20%",
    toggleActions: "play none none reverse"
  }
});

gsap.from(".payment-icon", { 
  opacity: 0, 
  scale: 0.5, 
  stagger: 0.2, 
  duration: 0.8, 
  ease: "back.out(1.7)",
  scrollTrigger: {
    trigger: ".payment-icon",
    start: "top 80%",
    end: "bottom 20%",
    toggleActions: "play none none reverse"
  }
});

gsap.from("#donate-button", { 
  opacity: 0, 
  y: 50, 
  duration: 1, 
  ease: "power3.out",
  scrollTrigger: {
    trigger: "#donate-button",
    start: "top 80%",
    end: "bottom 20%",
    toggleActions: "play none none reverse"
  }
});

// Hover animation for the donate button
const donateButton = document.querySelector("#donate-button");
donateButton.addEventListener("mouseenter", () => gsap.to("#donate-button", { scale: 1.05, duration: 0.3 }));
donateButton.addEventListener("mouseleave", () => gsap.to("#donate-button", { scale: 1, duration: 0.3 }));

// Continuous floating animation for payment icons
gsap.to(".payment-icon", {
  y: -10,
  duration: 1.5,
  ease: "sine.inOut",
  stagger: {
    each: 0.2,
    yoyo: true,
    repeat: -1
  }
});