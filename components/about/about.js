// Initialize GSAP
gsap.registerPlugin(ScrollTrigger);

// Animate headings
gsap.utils.toArray('h1, h2').forEach(heading => {
    gsap.from(heading, {
        opacity: 0,
        y: 50,
        duration: 1,
        scrollTrigger: {
            trigger: heading,
            start: 'top 80%',
            end: 'bottom 20%',
            toggleActions: 'play none none reverse'
        }
    });
});

// Animate content wrappers
gsap.utils.toArray('.content-wrapper').forEach(wrapper => {
    gsap.from(wrapper, {
        opacity: 0,
        x: wrapper.classList.contains('reverse') ? 50 : -50,
        duration: 1,
        scrollTrigger: {
            trigger: wrapper,
            start: 'top 80%',
            end: 'bottom 20%',
            toggleActions: 'play none none reverse'
        }
    });
});

// Animate stats section
gsap.from('.stats-section', {
    opacity: 0,
    y: 50,
    duration: 1,
    scrollTrigger: {
        trigger: '.stats-section',
        start: 'top 80%',
        end: 'bottom 20%',
        toggleActions: 'play none none reverse'
    }
});

// Animate stat numbers
function animateValue(obj, start, end, duration) {
    let startTimestamp = null;
    const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        obj.innerHTML = Math.floor(progress * (end - start) + start);
        if (progress < 1) {
            window.requestAnimationFrame(step);
        }
    };
    window.requestAnimationFrame(step);
}

const connectedGurjars = document.getElementById("connected-gurjars");
const connectedStates = document.getElementById("connected-states");
const eventsOrganized = document.getElementById("events-organized");

// Function to start stats animation
function startStatsAnimation() {
    // Reset values to 0 before starting animation
    connectedGurjars.innerHTML = "0";
    connectedStates.innerHTML = "0";
    eventsOrganized.innerHTML = "0";
    
    // Start animations
    animateValue(connectedGurjars, 0, 18, 2000);
    animateValue(connectedStates, 0, 13, 2000);
    animateValue(eventsOrganized, 0, 8, 2000);
}

// Create ScrollTrigger for stats section
ScrollTrigger.create({
    trigger: ".stats-section",
    start: "top 80%",
    end: "bottom 20%",
    onEnter: startStatsAnimation,
    onEnterBack: startStatsAnimation, // Trigger animation when scrolling back up
    toggleActions: "play none none reset" // Reset when scrolling away
});

// Animate achievements
gsap.from('.achievements-section li', {
    opacity: 0,
    x: -50,
    duration: 0.8,
    stagger: 0.2,
    scrollTrigger: {
        trigger: '.achievements-section',
        start: 'top 80%',
        end: 'bottom 20%',
        toggleActions: 'play none none reverse'
    }
});