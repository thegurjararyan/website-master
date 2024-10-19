// components/sponsors/sponsors.js
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

document.addEventListener('DOMContentLoaded', () => {
    const sponsorItems = gsap.utils.toArray('.sponsor-item');

    // Staggered reveal animation
    gsap.to(sponsorItems, {
        opacity: 1,
        y: 0,
        duration: 0.8,
        stagger: {
            amount: 1,
            grid: 'auto',
            from: 'center'
        },
        ease: 'power3.out',
        scrollTrigger: {
            trigger: '.sponsors-grid',
            start: 'top 80%'
        }
    });

    // Floating animation
    sponsorItems.forEach((item, index) => {
        gsap.to(item, {
            y: '+=10',
            duration: 2,
            repeat: -1,
            yoyo: true,
            ease: 'sine.inOut',
            delay: index * 0.1
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
});